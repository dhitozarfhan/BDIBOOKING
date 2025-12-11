<?php

namespace App\Livewire\Wbs;

use Livewire\Component;

class Response extends Component
{
    public $reportDetail;
    public $statusError = '';

    /**
     * Mount the component, fetch data based on the registration code from the URL.
     *
     * @return void
     */
    public function mount()
    {
        $this->reportDetail = session('reportDetail');
        $this->statusError = session('statusError');

        // If there is no report detail and no error, redirect to the status page 
        // to prevent accessing the response page directly.
        if (!$this->reportDetail && !$this->statusError) {
            return redirect()->route('wbs.status');
        }
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.wbs.response');
    }
}
