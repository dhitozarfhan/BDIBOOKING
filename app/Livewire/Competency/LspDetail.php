<?php

namespace App\Livewire\Competency;

use App\Services\SidiaClient;
use Livewire\Attributes\Url;
use Livewire\Component;
use RuntimeException;

class LspDetail extends Component
{
    #[Url(as: 'tab')]
    public string $activeTab = 'lsp';

    #[Url(as: 'scheme')]
    public ?int $expandedSchemeId = null;

    public int $lspId;
    public array $lsp = [];
    public array $assessors = [];
    public array $tuk = [];
    public array $schemes = [];
    public array $unitsByScheme = [];
    public ?string $error = null;
    public string $title = '';

    public function mount(SidiaClient $sidia, int $lspId): void
    {
        $this->lspId = $lspId;

        if (!in_array($this->activeTab, ['lsp', 'assessor', 'tuk', 'scheme'], true)) {
            $this->activeTab = 'lsp';
        }

        try {
            $payload = $sidia->post('competency/lsp', [
                'id_lsp' => $lspId,
            ]);

            $lsp = data_get($payload, 'data.lsp');

            if (!is_array($lsp) || empty($lsp)) {
                abort(404);
            }

            $this->lsp = $lsp;
            $this->assessors = $this->normalizeList(data_get($payload, 'data.asesor', []));
            $this->tuk = $this->normalizeList(data_get($payload, 'data.tuk', []));
            $this->schemes = $this->normalizeList(data_get($payload, 'data.skema', []));
            $this->unitsByScheme = $this->normalizeUnitsByScheme(data_get($payload, 'data.unit', []));
            $this->title = __('competency.lsp_details') . ' : ' . ($lsp['nama'] ?? '');
        } catch (RuntimeException $e) {
            $this->error = $e->getMessage();
        }

        if ($this->expandedSchemeId && !$this->hasScheme($this->expandedSchemeId)) {
            $this->expandedSchemeId = null;
        }
    }

    public function setTab(string $tab): void
    {
        if (!in_array($tab, ['lsp', 'assessor', 'tuk', 'scheme'], true)) {
            return;
        }

        $this->activeTab = $tab;

        if ($tab !== 'scheme') {
            $this->expandedSchemeId = null;
        }
    }

    public function toggleScheme(int $schemeId): void
    {
        $this->activeTab = 'scheme';
        $this->expandedSchemeId = $this->expandedSchemeId === $schemeId ? null : $schemeId;
    }

    public function render()
    {
        return view('livewire.competency.lsp-detail', [
            'lsp' => $this->lsp,
            'assessors' => $this->assessors,
            'tuk' => $this->tuk,
            'schemes' => $this->schemes,
            'unitsByScheme' => $this->unitsByScheme,
            'error' => $this->error,
            'activeTab' => $this->activeTab,
            'expandedSchemeId' => $this->expandedSchemeId,
        ])->title($this->title ?: __('competency.lsp_details'));
    }

    protected function normalizeList(mixed $items): array
    {
        if (!is_array($items)) {
            return [];
        }

        return array_values(array_filter($items, 'is_array'));
    }

    protected function normalizeUnitsByScheme(mixed $units): array
    {
        if (!is_array($units)) {
            return [];
        }

        $normalized = [];
        foreach ($units as $unit) {
            if (is_array($unit) && isset($unit['id_skema'])) {
                $normalized[$unit['id_skema']][] = $unit;
            }
        }

        return $normalized;
    }

    protected function hasScheme(int $schemeId): bool
    {
        foreach ($this->schemes as $scheme) {
            if (($scheme['id_skema'] ?? null) === $schemeId) {
                return true;
            }
        }

        return false;
    }
}
