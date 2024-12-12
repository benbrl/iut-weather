<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DailyReportEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $placeId;

    public function __construct($placeId)
    {
        $this->placeId = $placeId;
    }

    public function build()
    {
        return $this->subject('Daily Report for City')
                    ->view('emails.daily_report')
                    ->with(['placeId' => $this->placeId]);
    }
}
