<?php

namespace App\Livewire\Ibiza;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.ibiza.index')
            ->title('Ibiza - Inkubator Bisnis');
    }
}
