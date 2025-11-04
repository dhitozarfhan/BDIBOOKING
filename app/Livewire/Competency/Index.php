<?php

namespace App\Livewire\Competency;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.competency.index')
            ->title(__('competency.industrial_hr_competency'));
    }
}
