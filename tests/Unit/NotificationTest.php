<?php

namespace Tests\Unit;

use App\Models\User;
use App\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\Messages\MailMessage;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_notification_generates_mail(): void
    {
        $user = User::factory()->create();
        $token = 'test-token-123';
        
        $notification = new ResetPassword($token);
        $mailMessage = $notification->toMail($user);
        
        $this->assertInstanceOf(MailMessage::class, $mailMessage);
    }

    public function test_reset_password_notification_has_subject(): void
    {
        $user = User::factory()->create();
        $token = 'test-token-456';
        
        $notification = new ResetPassword($token);
        $mailMessage = $notification->toMail($user);
        
        $this->assertEquals('Restablecer Contraseña - FitSport', $mailMessage->subject);
    }

    public function test_reset_password_notification_has_action(): void
    {
        $user = User::factory()->create();
        $token = 'test-token-789';
        
        $notification = new ResetPassword($token);
        $mailMessage = $notification->toMail($user);
        
        $this->assertNotEmpty($mailMessage->actionUrl);
    }

    public function test_reset_password_notification_has_greeting(): void
    {
        $user = User::factory()->create();
        $token = 'test-token-abc';
        
        $notification = new ResetPassword($token);
        $mailMessage = $notification->toMail($user);
        
        $this->assertEquals('¡Hola!', $mailMessage->greeting);
    }
}
