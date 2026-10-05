<?php

namespace App\Services\Sources;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class LeverSource
{
    public function fetch(array $source): array
    {
        $url = 'https://api.lever.co/v0/postings/' . urlencode($source['site']);

        $response = Http::timeout(20)
            ->acceptJson()
            ->get($url, [
                'mode' => 'json',
            ]);

        if (!$response->successful()) {
            throw new RuntimeException(
                'Lever HTTP ' . $response->status() . ': ' .
                mb_substr($response->body(), 0, 500)
            );
        }

        $data = $response->json();

        if (!is_array($data)) {
            throw new RuntimeException(
                'Lever returned an unexpected response type: ' .
                gettype($data)
            );
        }

        /*
         * Lever normally returns an array of postings.
         * Protect against unexpected API responses so one company
         * cannot crash the entire job scan.
         */
        if (isset($data['data']) && is_array($data['data'])) {
            $data = $data['data'];
        }

        $jobs = [];

        foreach ($data as $job) {
            if (!is_array($job)) {
                continue;
            }

            $categories = $job['categories'] ?? [];

            if (!is_array($categories)) {
                $categories = [];
            }

            $location = $categories['location'] ?? '';

            if (is_array($location)) {
                $location = implode(', ', $location);
            }

            $description = $job['descriptionPlain']
                ?? $job['description']
                ?? '';

            $createdAt = $job['createdAt'] ?? null;

            $postedAt = null;

            if (is_numeric($createdAt)) {
                $postedAt = now()->createFromTimestampMs((int) $createdAt);
            }

            $jobs[] = [
                'source' => 'lever',
                'source_id' => (string) ($job['id'] ?? ''),
                'company' => $source['company'],
                'title' => (string) ($job['text'] ?? ''),
                'location' => (string) $location,
                'description' => is_string($description)
                    ? strip_tags($description)
                    : '',
                'url' => (string) ($job['hostedUrl'] ?? $job['applyUrl'] ?? ''),
                'posted_at' => $postedAt,
            ];
        }

        return $jobs;
    }
}