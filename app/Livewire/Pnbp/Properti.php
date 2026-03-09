<?php

namespace App\Livewire\Pnbp;

use Livewire\Component;
use App\Models\Property;

class Properti extends Component
{
    public function render()
    {
        $properties = Property::with('propertyType')->get();

        return view('livewire.pnbp.properti', [
            'properties' => $properties,
        ])->title('Properti - Pelayanan PNBP');
    }
}
