<?php

namespace App\Livewire\Competency;

use App\Services\SidiaClient;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use RuntimeException;

class LspDetail extends Component
{
    use WithPagination;

    #[Url(as: 'tab')]
    public string $activeTab = 'lsp';

    #[Url(as: 'scheme')]
    public ?int $expandedSchemeId = null;

    public int $lspId;
    public array $lsp = [];
    public array $allAssessors = [];
    public array $allTuk = [];
    public array $allSchemes = [];
    public array $unitsByScheme = [];
    public ?string $error = null;
    public string $title = '';
    public string $backUrl = '';

    public function mount(SidiaClient $sidia, int $lspId): void
    {
        $this->lspId = $lspId;

        $previousUrl = url()->previous();
        $baseCompetencyUrl = route('competency.index');

        if (str_starts_with($previousUrl, $baseCompetencyUrl)) {
            $this->backUrl = $previousUrl;
        } else {
            $this->backUrl = route('competency.section', ['section' => 'scheme']);
        }

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
            $this->allAssessors = $this->normalizeList(data_get($payload, 'data.asesor', []));
            $this->allTuk = $this->normalizeList(data_get($payload, 'data.tuk', []));
            $this->allSchemes = $this->normalizeList(data_get($payload, 'data.skema', []));
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
        $this->resetPage('assessorsPage');
        $this->resetPage('tukPage');
        $this->resetPage('schemesPage');

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
        $assessorsCollection = new Collection($this->allAssessors);
        $tukCollection = new Collection($this->allTuk);
        $schemesCollection = new Collection($this->allSchemes);
        $perPage = 10;

        $paginatedAssessors = new LengthAwarePaginator(
            $assessorsCollection->forPage($this->getPage('assessorsPage'), $perPage),
            $assessorsCollection->count(),
            $perPage,
            $this->getPage('assessorsPage'),
            ['pageName' => 'assessorsPage']
        );

        $paginatedTuk = new LengthAwarePaginator(
            $tukCollection->forPage($this->getPage('tukPage'), $perPage),
            $tukCollection->count(),
            $perPage,
            $this->getPage('tukPage'),
            ['pageName' => 'tukPage']
        );

        $paginatedSchemes = new LengthAwarePaginator(
            $schemesCollection->forPage($this->getPage('schemesPage'), $perPage),
            $schemesCollection->count(),
            $perPage,
            $this->getPage('schemesPage'),
            ['pageName' => 'schemesPage']
        );

        return view('livewire.competency.lsp-detail', [
            'lsp' => $this->lsp,
            'assessors' => $paginatedAssessors,
            'tuk' => $paginatedTuk,
            'schemes' => $paginatedSchemes,
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
        foreach ($this->allSchemes as $scheme) {
            if (($scheme['id_skema'] ?? null) == $schemeId) {
                return true;
            }
        }

        return false;
    }
}
