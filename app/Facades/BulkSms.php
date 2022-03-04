<?php
namespace App\Facades;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use App\Facades\BaseFacade;
// use App\Repositories\SmsInterface;
use Illuminate\Support\Facades\Config;
use Twilio\Rest\Client;


class BulkSms 
{
    use Queueable, SerializesModels;

    protected $to;

    protected $message;

    public function __construct($to, $message)
    {
        $this->to = $to;
        $this->message = $message;
    }

    public function send()
    {
        try {
           $client = new Client(Config::get('sms.twilio.sid'), Config::get('sms.twilio.token'));
           $client->messages->create($this->to, [
               'from' => Config::get('sms.twilio.from'),
               'body' => $this->message
           ]);
           return;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}