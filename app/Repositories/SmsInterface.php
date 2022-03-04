<?php
namespace App\Repositories;

interface SmsInterface

{
   public function sendSMS($phone, $message);
}
?>