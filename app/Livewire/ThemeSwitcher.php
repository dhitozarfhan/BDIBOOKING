<?php
namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ThemeSwitcher extends Component
{
    /**
     * mode: 'default' | 'prefersdark'
     */
    public string $mode = 'default';

    public function mount()
    {
        $this->mode = session('theme-mode', 'default');
    }

    public function setMode(string $mode)
    {
        $this->mode = in_array($mode, ['default', 'prefersdark']) ? $mode : 'default';
        session(['theme-mode' => $this->mode]);

        $this->dispatch('theme-changed', mode: $this->mode);
    }

    public function toggle()
    {
        $this->setMode($this->mode === 'prefersdark' ? 'default' : 'prefersdark');
    }

    public function render()
    {
        return view('livewire.theme-switcher');
    }
}