<?php

namespace App\Livewire;

use Livewire\Component;

class LanguageSwitcher extends Component
{
    public string $locale = 'id';
    public array $available = [
        'en' => 'English',
        'id' => 'Bahasa Indonesia',
    ];

    public function mount()
    {
        $this->locale = session('locale', 'en');

        // (opsional) baca preferensi user dari database
        // if (Auth::check() && Auth::user()->locale) {
        //     $this->locale = Auth::user()->locale;
        //     session(['locale' => $this->locale]);
        // }
    }

    public function setLocale(string $locale)
    {
        if (! array_key_exists($locale, $this->available)) {
            return;
        }

        $this->locale = $locale;
        session(['locale' => $locale]);
        app()->setLocale($locale);

        // (opsional) simpan ke user
        // if (Auth::check()) {
        //     Auth::user()->forceFill(['locale' => $locale])->save();
        // }

        // kasih sinyal ke front-end kalau mau tampilkan toast, dsb
        $this->dispatch('locale-changed', locale: $locale);
    }

    public function render()
    {
        return view('livewire.language-switcher');
    }
}
