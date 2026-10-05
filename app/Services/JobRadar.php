<?php
namespace App\Services;
use App\Models\Job;
use App\Services\Sources\GreenhouseSource;
use App\Services\Sources\LeverSource;
use Illuminate\Support\Facades\Log;
class JobRadar {
    public function __construct(private GreenhouseSource $greenhouse, private LeverSource $lever, private JobMatcher $matcher) {}
    
    public function scan(): array {
        $profile = config('job-radar.profile'); 
        $jobs=[];
        
        foreach(config('job-radar.greenhouse',[]) as $source) {
            try { 
                $jobs=[...$jobs,...$this->greenhouse->fetch($source)]; 
            } catch(\Throwable $e) { 
                Log::error('Greenhouse failed',['error'=>$e->getMessage(),'source'=>$source]); 
            }
        }
        foreach(config('job-radar.lever',[]) as $source) {
            try { 
                $jobs=[...$jobs,...$this->lever->fetch($source)]; 
            } catch(\Throwable $e) { 
                Log::error('Lever failed',['error'=>$e->getMessage(),'source'=>$source]); 
            }
        }

        $ranked=[];
        foreach($jobs as $job) {
            if(!$job['title']||!$job['url']) continue;
            $hash = hash('sha256',strtolower(trim(($job['company']??'').'|'.($job['title']??'').'|'.($job['location']??'').'|'.($job['url']??''))));
            if(Job::where('hash',$hash)->exists()) {
                continue;
            }
            $match = $this->matcher->score($job,$profile); 
            if($match['score'] < config('job-radar.min_score_to_email')) {
                continue;
            }
            $job['hash'] = $hash; 
            $job['match_score'] = $match['score']; 
            $job['match_reasons'] = $match['reasons']; 
            $job['match_concerns'] = $match['concerns']; 
            $ranked[] = $job;
        }
        usort($ranked,fn($a,$b) => $b['match_score'] <=> $a['match_score']);
        return array_slice($ranked,0,config('job-radar.max_jobs_per_email'));
    }

    public function persist(array $jobs): void { 
        foreach($jobs as $job) Job::create($job); 
    }
}
