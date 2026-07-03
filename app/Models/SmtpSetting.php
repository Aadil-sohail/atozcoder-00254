<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmtpSetting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'mailer',
        'host',
        'username',
        'port',
        'encryption',
        'password',
        'from_address',
    ];

    /**
     * Set the application's mail configuration based on these settings.
     *
     * @return void
     */
    // public static function setMailConfig()
    // {
    //     $smtp = self::first();
    //     if ($smtp) {
    //         \Illuminate\Support\Facades\Log::info("Setting mail config: host={$smtp->host}, port={$smtp->port}, user={$smtp->username}, from={$smtp->from_address}");

    //         config([
    //             'mail.default' => 'smtp',
    //             'mail.mailers.smtp.transport' => $smtp->mailer,
    //             'mail.mailers.smtp.host' => $smtp->host,
    //             'mail.mailers.smtp.port' => $smtp->port,
    //             'mail.mailers.smtp.encryption' => $smtp->encryption,
    //             'mail.mailers.smtp.username' => $smtp->username,
    //             'mail.mailers.smtp.password' => $smtp->password,
    //             'mail.from.address' => $smtp->from_address,
    //             'mail.from.name' => config('app.name'),
    //         ]);

    //         \Illuminate\Support\Facades\Mail::purge('smtp');
    //         \Illuminate\Support\Facades\Log::info("Mail config applied and SMTP purged.");
    //     } else {
    //         \Illuminate\Support\Facades\Log::warning("SmtpSetting::setMailConfig called but no settings found in database.");
    //     }
    // }
}
