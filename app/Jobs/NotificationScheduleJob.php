<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotificationScheduleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */

    protected $titel;
    protected $body;
    protected $key;


    public function __construct($titel,$body,$key)
    {
        $this->tite=$titel;
        $this->body=$body;
        $this->key=$key;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        NotificationController::notify($this->titel,$this->body,$this->key);
    }
}
