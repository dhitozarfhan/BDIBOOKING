<?php

namespace App\Livewire\Competency;

use App\Services\SidiaClient;
use Livewire\Attributes\Url;
use Livewire\Component;
use RuntimeException;

class SkkniDetail extends Component
{
    #[Url(as: 'tab')]
    public string $activeTab = 'details';

    public string $skkniId;
    public array $skkni = [];
    public array $units = [];
    public ?string $error = null;
    public string $title = '';

    public function mount(SidiaClient $sidia, string $skkniId): void
    {
        $this->skkniId = $skkniId;

        if (!in_array($this->activeTab, ['details', 'units'], true)) {
            $this->activeTab = 'details';
        }

        try {
            $payload = $sidia->post('competency/skkni', ['skkni_id' => $skkniId]);

            $data = data_get($payload, 'data');

            // Ensure $data is an array and not empty
            if (!is_array($data) || empty($data)) {
                abort(404);
            }

            // Try to get 'skkni' from 'data', otherwise use 'data' itself
            // Ensure $skkniData is an array
            $skkniData = (is_array($data) && isset($data['skkni'])) ? $data['skkni'] : $data;

            // If the API returns a list for a single item query, take the first one.
            // Ensure $skkni is an array
            $skkni = (is_array($skkniData) && isset($skkniData[0]) && is_array($skkniData[0])) ? $skkniData[0] : $skkniData;

            // Re-validate to ensure we have a valid SKKNI object before proceeding.
            if (!is_array($skkni) || empty($skkni) || !isset($skkni['skkni_id'])) {
                abort(404);
            }

            $this->skkni = $skkni;
            $this->units = $this->normalizeUnits(data_get($data, 'unit', []));
            $this->title = $skkni['judul'] ?? __('competency.skkni_details');
        } catch (RuntimeException $e) {
            $this->error = $e->getMessage();
        }
    }

    public function switchTab(string $tab): void
    {
        if (in_array($tab, ['details', 'units'], true)) {
            $this->activeTab = $tab;
        }
    }

    public function render()
    {
        return view('livewire.competency.skkni-detail', [
            'skkni' => $this->skkni,
            'units' => $this->units,
            'error' => $this->error,
            'activeTab' => $this->activeTab,
        ])->title($this->title ?: __('competency.skkni_details'));
    }

    protected function normalizeUnits(mixed $units): array
    {
        if (!is_array($units)) {
            return [];
        }

        return array_values(array_filter($units, 'is_array'));
    }
}
