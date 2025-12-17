<?php

namespace Modules\Reports\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Modules\Reports\Entities\Report; // Report modelinizin namespace-i
use Illuminate\Support\Facades\URL; // Link yaratmaq üçün

class AdminReportsMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @param Collection<Report> $reports Bütün yaradılmış Report obyektləri (PDF linkləri bu obyektlərdədir)
     * @param string $tenantId Tenant ID-si üçün
     */
    public function __construct(
        public Collection $reports,
        public string $tenantId
    )
    {
        //
    }

    /**
     * Mesajı qurur.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Hesabat: '{$this->tenantId}' üçün Yeni Admin Reportları")
            ->view('reports::emails.reports_mail', [
                'reports' => $this->reports,
                'tenantId' => $this->tenantId,
                'reportCount' => $this->reports->count(),
            ]);
    }
}
