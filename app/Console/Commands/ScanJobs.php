<?php

namespace App\Console\Commands;

use App\Mail\JobDigestMail;
use App\Services\JobRadar;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class ScanJobs extends Command
{
    protected $signature = 'job-radar:scan {--dry-run : Do not send email or persist jobs}';

    protected $description = 'Scan configured job sources, rank jobs, and email the top matches.';

    public function handle(JobRadar $radar): int
    {
        $this->info('Scanning configured job sources...');

        $jobs = $radar->scan();

        $this->info('Found ' . count($jobs) . ' new matches.');

        if ($this->option('dry-run')) {
            foreach ($jobs as $i => $job) {
                $this->line(sprintf(
                    '#%d [%d/100] %s — %s | %s',
                    $i + 1,
                    $job['match_score'],
                    $job['title'],
                    $job['company'],
                    $job['url']
                ));
            }

            return self::SUCCESS;
        }

        if (!$jobs) {
            $this->info('No email sent because there were no new matches.');

            return self::SUCCESS;
        }

        /*
         * Send the email FIRST.
         *
         * Only persist the jobs after Gmail successfully accepts
         * the message. This prevents a Gmail failure from causing
         * the jobs to be permanently marked as already processed.
         */
        Mail::to(config('job-radar.to'))
            ->send(new JobDigestMail($jobs));

        $radar->persist($jobs);

        $this->info(
            'Digest sent to ' . config('job-radar.to')
        );

        return self::SUCCESS;
    }
}