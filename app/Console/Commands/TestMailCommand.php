<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestMailCommand extends Command
{
    protected $signature = 'mail:test {email? : Alamat tujuan (default: MAIL_FROM_ADDRESS)}';

    protected $description = 'Kirim email percobaan untuk memverifikasi konfigurasi email/SMTP';

    public function handle(): int
    {
        $to = $this->argument('email') ?: config('mail.from.address');
        $mailer = config('mail.default');

        $this->line('Mailer : '.$mailer);
        $this->line('Host   : '.config('mail.mailers.smtp.host').':'.config('mail.mailers.smtp.port'));
        $this->line('Dari   : '.config('mail.from.address').' ('.config('mail.from.name').')');
        $this->line('Tujuan : '.$to);

        try {
            Mail::raw(
                'Tes koneksi email dari aplikasi '.config('app.name').'.'.PHP_EOL.PHP_EOL
                .'Dikirim: '.now()->translatedFormat('d M Y H:i:s').PHP_EOL
                .'Kalau email ini masuk, konfigurasi email sudah benar.',
                fn ($message) => $message->to($to)->subject('Tes Email '.config('app.name'))
            );
        } catch (\Throwable $exception) {
            $this->error('GAGAL mengirim: '.$exception->getMessage());
            $this->newLine();
            $this->warn('Periksa MAIL_* di .env lalu jalankan: php artisan config:clear');

            return self::FAILURE;
        }

        if ($mailer === 'log') {
            $this->info('Mailer "log" aktif: email tidak dikirim, tapi ditulis ke storage/logs/laravel.log.');
        } else {
            $this->info('Email berhasil dikirim ke '.$to.'. Cek inbox (dan folder spam).');
        }

        return self::SUCCESS;
    }
}
