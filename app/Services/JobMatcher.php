<?php
namespace App\Services;
class JobMatcher {
    public function score(array $job, array $profile): array {
        $title=$this->norm($job['title']??''); 
        $location=$this->norm($job['location']??''); 
        $description=$this->norm($job['description']??''); 
        $text="$title $location $description";
        $score=0; 
        $reasons=[]; 
        $concerns=[];
        if ($this->has($title,$profile['target_roles'])) { 
            $score+=25; 
            $reasons[]='Role title matches your backend targets.'; 
        } elseif ($this->has($title,['backend','php','laravel','java','spring'])) { 
            $score+=18; $reasons[]='Role is clearly backend-oriented.'; 
        }

        $hits=[]; 
        foreach($profile['skills'] as $skill) {
            if(str_contains($text,strtolower($skill))) {
                $hits[]=$skill;
            }
        }

        $hits=array_values(array_unique($hits)); 
        $score+=min(30,count($hits)*3);
        if($hits) {
            $reasons[]='Matches skills: '.implode(', ',array_slice($hits,0,8)).'.';
        }
        if($this->has($text,$profile['remote_keywords'])) { 
            $score+=15; $reasons[]='Remote-friendly wording detected.'; 
        }

        if($this->has($text,$profile['preferred_locations'])) { 
            $score+=10; $reasons[]='Location appears compatible with your target locations.'; 
        }

        if($this->has($text,['visa sponsorship','visa support','relocation','work permit'])) { 
            $score+=8; $reasons[]='Mentions sponsorship, relocation, or work-permit support.'; 
        }

        if($this->has($text,['fintech','payment','payments','kyc','kyb','banking','wallet'])) { 
            $score+=7; $reasons[]='Relevant fintech/payments/KYC domain signal.'; 
        }

        foreach($profile['negative_keywords'] as $bad) {
            if(str_contains($text,strtolower($bad))) { 
                $score-=30; $concerns[]="Contains an excluded/less-relevant role signal: $bad."; 
            }
        }

        foreach($profile['seniority_negative_keywords'] as $bad) {
            if(str_contains($title,strtolower($bad))) { 
                $score-=25; $concerns[]='Senior level appears above the target range.'; 
            }
        }

        if(preg_match('/(\d+)\+?\s*(?:years|yrs)/i',$text,$m) && (int)$m[1]>=6) { 
            $score-=20; $concerns[]='Posting appears to request 6+ years of experience.'; 
        }

        return ['score'=>max(0,min(100,$score)),'reasons'=>array_values(array_unique($reasons)),'concerns'=>array_values(array_unique($concerns))];
    }

    private function norm(string $v): string { 
        return trim(preg_replace('/\s+/',' ',strtolower($v))); 
    }

    private function has(string $haystack,array $needles): bool { 
        foreach($needles as $n) {
            if($n!==''&&str_contains($haystack,strtolower($n))) {
                return true; 
            }
        }
        return false; 
    }
}
