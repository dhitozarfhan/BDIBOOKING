<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpVerification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $otpCode,
        public string $recipientName,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kode Verifikasi Email - BDI Yogyakarta',
        );
    }

    public function build()
    {
        return $this->html("
            <div style='font-family: Arial, sans-serif; max-width: 480px; margin: 0 auto; padding: 32px 24px;'>
                <div style='text-align: center; margin-bottom: 32px;'>
                    <h2 style='color: #1a1a1a; margin: 0 0 8px 0; font-size: 22px;'>Verifikasi Email Anda</h2>
                    <p style='color: #666; margin: 0; font-size: 14px;'>Halo <strong>{$this->recipientName}</strong>, masukkan kode berikut untuk melanjutkan pendaftaran.</p>
                </div>
                <div style='background: linear-gradient(135deg, #4f46e5, #7c3aed); border-radius: 16px; padding: 28px; text-align: center; margin-bottom: 24px;'>
                    <p style='color: rgba(255,255,255,0.7); font-size: 12px; margin: 0 0 8px 0; text-transform: uppercase; letter-spacing: 2px;'>Kode OTP</p>
                    <p style='color: #fff; font-size: 36px; font-weight: bold; letter-spacing: 8px; margin: 0;'>{$this->otpCode}</p>
                </div>
                <div style='background: #fff8e1; border-radius: 8px; padding: 12px 16px; border-left: 4px solid #f59e0b;'>
                    <p style='color: #92400e; margin: 0; font-size: 13px;'>⚠️ Kode ini berlaku selama <strong>5 menit</strong>. Jangan bagikan kode ini kepada siapapun.</p>
                </div>
                <p style='color: #999; font-size: 11px; text-align: center; margin-top: 24px;'>Email ini dikirim otomatis oleh sistem BDI Yogyakarta.</p>
            </div>
        ");
    }
}
