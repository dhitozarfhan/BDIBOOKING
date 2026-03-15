<?php

namespace App\Livewire\Pnbp;

use App\Models\Property;
use Livewire\Component;

class DetailProperti extends Component
{
    public Property $property;

    public function mount($id)
    {
        $this->property = Property::findOrFail($id);
    }

    public function render()
    {
        return view('livewire.pnbp.detail-properti')->title($this->property->name);
    }
}
