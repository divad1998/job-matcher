<?php
namespace App\Services\Sources;
use Illuminate\Support\Facades\Http;
class GreenhouseSource {
    public function fetch(array $source): array {
        $response = Http::timeout(25)->acceptJson()
                        ->get('https://boards-api.greenhouse.io/v1/boards/'.rawurlencode($source['token']).'/jobs', ['content'=>'true'])
                        ->throw();

        return collect($response->json('jobs', []))->map(fn(array $job) => [
            'source'=>'greenhouse','source_id'=>(string)($job['id']??''),'company'=>$source['company']??$source['token'],
            'title'=>trim($job['title']??''),'location'=>trim($job['location']['name']??''),
            'description'=>strip_tags(html_entity_decode($job['content']??'')),'url'=>$job['absolute_url']??'',
            'posted_at'=>$job['updated_at']??null,
        ])->values()->all();
    }
}
