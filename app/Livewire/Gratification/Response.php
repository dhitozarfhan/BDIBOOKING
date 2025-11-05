<?php

namespace App\Livewire\Gratification;

use App\Models\Gratification as GratificationModel;
use Livewire\Component;

class Response extends Component
{
    public $reportDetail;
    public $statusError = '';

    /**
     * Mount the component, fetch data based on the registration code from the URL.
     *
     * @param string $code
     * @return void
     */
    public function mount($code)
    {
        $gratification = GratificationModel::where('kode_register', $code)->first();

        if ($gratification) {
            $process = $gratification->processes()->latest()->first();

            $reportDetail = new \stdClass();
            $reportDetail->subject = $gratification->judul_laporan;
            $reportDetail->name = $gratification->nama_pelapor;
            $reportDetail->mobile = $gratification->telepon ? substr($gratification->telepon, 0, -4) . 'xxxx' : '-';
            $reportDetail->time_insert = $gratification->created_at;
            $reportDetail->content = $gratification->uraian_laporan;

            if ($process) {
                $reportDetail->status = $process->status;
                $reportDetail->answer = $process->jawaban;
            } else {
                $reportDetail->status = 'I'; // Default to Inisiasi
                $reportDetail->answer = null;
            }

            // Attachment is from the original report's data_dukung
            $reportDetail->attachment = $gratification->data_dukung;
            
            $this->reportDetail = $reportDetail;

        } else {
            $this->statusError = 'Kode register tidak ditemukan dalam sistem kami.';
        }
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.gratification.response');
    }
}
