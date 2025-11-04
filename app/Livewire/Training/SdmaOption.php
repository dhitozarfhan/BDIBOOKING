<?php

namespace App\Livewire\Training;

use Livewire\Component;

class SdmaOption extends Component
{
    public $id_diklat;
    public $slug;

    public function mount($id_diklat, $slug = null)
    {
        $this->id_diklat = $id_diklat;
        $this->slug = $slug;
    }

    public function render()
    {
        return view('livewire.training.sdma-option')->title('Pilih Asal Pendaftar');
    }
}
