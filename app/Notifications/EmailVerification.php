<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class EmailVerification extends Notification
{
    use Queueable;
    
    private $email;
    
    private $guard;

    private $user;
    
    private $verificationLink;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($name, $email, $guard = 'web')
    {   
        $this->name  = $name;
        $this->email = $email;
        $this->guard = $guard;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                ->subject(config('app.name') . 'Email Activation')
                ->greeting('Hello, '. $this->name)
                ->line('Thank you for signing up with '. config('app.name') .'. Please click the button below to verify your email')
                ->action('Verify Email', $this->verificationUrl($notifiable))
                ->line('Thank you for using '. config('app.name') .'!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    /*
    * Build the verification URL
    *
    * @return URL
    */
    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(
                Config::get('auth.verification.expire', 60)),
                [
                    'id' => $notifiable->getKey(),
                    'hash' => sha1($notifiable->getEmailForVerification()),
                ]     
            ); 
    }
}
