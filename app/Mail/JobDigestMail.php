<?php
namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
class JobDigestMail extends Mailable {
    use Queueable, SerializesModels;
    public function __construct(public array $jobs) {}
    
    public function build(): self { 
        return $this->subject('Job Radar — '.count($this->jobs).' new matches')->view('emails.job-digest'); 
    }
}
