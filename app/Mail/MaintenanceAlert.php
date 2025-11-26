<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MaintenanceAlert extends Mailable
{
    use Queueable, SerializesModels;

    public $isMaintenance; // True = System Down (Red), False = System Up (Green)

    public function __construct(bool $isMaintenance)
    {
        $this->isMaintenance = $isMaintenance;
    }

    public function build()
    {
        $subject = $this->isMaintenance
            ? '🚨 URGENT: BLACKFILE SYSTEM LOCKDOWN INITIATED'
            : '✅ SYSTEM RESTORED: BLACKFILE ONLINE';

        return $this->subject($subject)
            ->view('emails.admin.maintenance_alert');
    }
}
