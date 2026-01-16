<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use PDF;

class DailyClosureReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public $closures,
        public $date
    ) {}

    public function build()
    {
        $pdf = PDF::loadView('admin.pdf.daily-closures', [
            'closures' => $this->closures,
            'date' => $this->date,
        ]);

        return $this->subject('Reporte de cierre - ' . $this->date)
            ->attachData($pdf->output(), 'daily-closures.pdf')
            ->view('emails.cierre');
    }
}
