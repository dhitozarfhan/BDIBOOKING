<?php

namespace App\Livewire\Pnbp;

use Livewire\Component;
use App\Models\Training;

class Index extends Component
{
    public function render()
    {
        return view('livewire.pnbp.index', [
            'trainings' => Training::where('type', Training::TYPE_PNBP)
                ->where('is_published', true)
                ->orderBy('start_date', 'asc')
                ->get(),
        ]);
    }
}
