<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Facades\BulkSms;

class smsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $numbers;

    protected $message;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($numbers, $message)
    {
        $this->numbers = $numbers;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->numbers as $number) {
            $sendMessages = new BulkSms($number, $this->message);
            $sendMessages->send();
        }
    }
}
