<?php

namespace App\Livewire\Pnbp;

use Livewire\Component;
use App\Models\Training;

class Pelatihan extends Component
{
    public $selectedCategory = '';

    public function render()
    {
        $query = Training::where('is_published', true);

        if ($this->selectedCategory) {
            $query->where('type', $this->selectedCategory);
        }

        $categories = Training::whereNotNull('type')
            ->where('is_published', true)
            ->distinct()
            ->pluck('type');

        return view('livewire.pnbp.pelatihan', [
            'trainings' => $query->orderBy('start_date', 'asc')->get(),
            'categories' => $categories,
        ])->title('Pelatihan - Pelayanan PNBP');
    }
}
