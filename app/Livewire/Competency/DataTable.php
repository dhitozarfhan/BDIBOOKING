<?php

namespace App\Livewire\Competency;

use App\Services\SidiaClient;
use Illuminate\Support\Str;
use Livewire\Component;
use RuntimeException;

class DataTable extends Component
{
    protected const SECTIONS = ['skkni', 'lsp', 'assessor', 'tuk', 'scheme'];

    public string $section;
    public array $columns = [];
    public array $rows = [];
    public ?string $error = null;
    public string $title;

    public function mount(string $section, SidiaClient $sidia): void
    {
        $section = strtolower($section);
        if (!in_array($section, self::SECTIONS, true)) {
            abort(404);
        }

        $this->section = $section;
        $this->columns = $this->columnsFor($section);
        $this->title = $this->titleFor($section);

        try {
            $payload = $sidia->post('competency/' . $section);
            $this->rows = $this->rowsFromPayload($section, $payload);
        } catch (RuntimeException $e) {
            $this->error = $e->getMessage();
        }
    }

    public function render()
    {
        return view('livewire.competency.data-table', [
            'columns' => $this->columns,
            'rows' => $this->rows,
            'error' => $this->error,
            'section' => $this->section,
            'title' => $this->title,
        ])->title($this->title);
    }

    protected function titleFor(string $section): string
    {
        return match ($section) {
            'skkni' => __('competency.competency_skkni_list'),
            'lsp' => __('competency.competency_lsp_list'),
            'assessor' => __('competency.competency_assessor_list'),
            'tuk' => __('competency.competency_tuk_list'),
            'scheme' => __('competency.competency_scheme_list'),
            default => __('competency.industrial_hr_competency'),
        };
    }

    protected function columnsFor(string $section): array
    {
        return match ($section) {
            'skkni' => [
                ['key' => 'nomor', 'label' => __('competency.skkni_ministerial_decree_number')],
                ['key' => 'judul', 'label' => __('competency.skkni_title'), 'type' => 'link'],
                ['key' => 'kategori', 'label' => __('competency.skkni_category')],
                ['key' => 'pokok', 'label' => __('competency.skkni_core')],
                ['key' => 'instansi', 'label' => __('competency.skkni_agency')],
                ['key' => 'status', 'label' => __('competency.skkni_status'), 'type' => 'badge'],
                ['key' => 'total_unit', 'label' => __('competency.unit_count')],
            ],
            'lsp' => [
                ['key' => 'logo', 'label' => '', 'type' => 'image'],
                ['key' => 'nama', 'label' => __('competency.lsp_name'), 'type' => 'link'],
                ['key' => 'jenis', 'label' => __('competency.lsp_type')],
                ['key' => 'alamat', 'label' => __('competency.address')],
                ['key' => 'kode_kota', 'label' => __('competency.city_code')],
                ['key' => 'kota', 'label' => __('competency.city')],
                ['key' => 'provinsi', 'label' => __('competency.province')],
                ['key' => 'jumlah_asesor', 'label' => __('competency.assessor_count')],
                ['key' => 'jumlah_tuk', 'label' => __('competency.tuk_count')],
                ['key' => 'jumlah_skema', 'label' => __('competency.scheme_count')],
            ],
            'assessor' => [
                ['key' => 'nama', 'label' => __('competency.assessor_name')],
                ['key' => 'kelamin', 'label' => __('competency.m_or_f'), 'type' => 'gender'],
                ['key' => 'nomor_reg', 'label' => __('competency.register_number')],
                ['key' => 'lsp', 'label' => __('competency.lsp_name'), 'type' => 'link'],
                ['key' => 'umur', 'label' => __('competency.age')],
                ['key' => 'pendidikan', 'label' => __('competency.education')],
                ['key' => 'pekerjaan', 'label' => __('competency.profession')],
                ['key' => 'kode_kota', 'label' => __('competency.city_code')],
                ['key' => 'kota', 'label' => __('competency.city')],
                ['key' => 'provinsi', 'label' => __('competency.province')],
            ],
            'tuk' => [
                ['key' => 'nama', 'label' => __('competency.tuk_name')],
                ['key' => 'jenis', 'label' => __('competency.tuk_type')],
                ['key' => 'lsp', 'label' => __('competency.lsp_name'), 'type' => 'link'],
                ['key' => 'alamat', 'label' => __('competency.address')],
                ['key' => 'kode_kota', 'label' => __('competency.city_code')],
                ['key' => 'kota', 'label' => __('competency.city')],
                ['key' => 'provinsi', 'label' => __('competency.province')],
                ['key' => 'mobile', 'label' => __('competency.handphone')],
                ['key' => 'telepon', 'label' => __('competency.phone')],
                ['key' => 'fax', 'label' => __('competency.fax')],
                ['key' => 'email', 'label' => __('competency.email')],
            ],
            'scheme' => [
                ['key' => 'nama', 'label' => __('competency.scheme_name')],
                ['key' => 'jenis', 'label' => __('competency.scheme_type')],
                ['key' => 'kode', 'label' => __('competency.scheme_code')],
                ['key' => 'lsp', 'label' => __('competency.lsp_name'), 'type' => 'link'],
                ['key' => 'bidang', 'label' => __('competency.scheme_sector')],
                ['key' => 'subsektor', 'label' => __('competency.scheme_subsector')],
                ['key' => 'total_unit', 'label' => __('competency.unit_count'), 'type' => 'badge'],
            ],
            default => [],
        };
    }

    protected function rowsFromPayload(string $section, array $payload): array
    {
        return match ($section) {
            'skkni' => $this->mapSkkni(data_get($payload, 'data.skkni')),
            'lsp' => $this->mapLsp(data_get($payload, 'data.lsp')),
            'assessor' => $this->mapAssessor(data_get($payload, 'data.asesor')),
            'tuk' => $this->mapTuk(data_get($payload, 'data.tuk')),
            'scheme' => $this->mapScheme(data_get($payload, 'data.skema')),
            default => [],
        };
    }

    protected function mapSkkni(mixed $items): array
    {
        $items = $this->normalizeList($items);

        return array_map(function (array $item): array {
            $availability = strtolower((string) ($item['availability'] ?? ''));
            $statusLabel = match ($availability) {
                'applied' => __('competency.skkni_status_applied'),
                'replaced' => __('competency.skkni_status_replaced'),
                'cancelled' => __('competency.skkni_status_cancelled'),
                default => __('competency.unknown_status'),
            };

            return [
                'nomor' => $item['nomor'] ?? '-',
                'judul' => [
                    'text' => $item['judul'] ?? '-',
                    'url' => isset($item['skkni_id'])
                        ? route('competency.skkni.show', [
                            'skkniId' => $item['skkni_id'],
                            'slug' => Str::slug((string) ($item['judul'] ?? '')),
                        ])
                        : null,
                ],
                'kategori' => $item['category'] ?? '-',
                'pokok' => $item['core'] ?? '-',
                'instansi' => $item['agency'] ?? '-',
                'status' => [
                    'label' => $statusLabel,
                    'tone' => match ($availability) {
                        'applied' => 'success',
                        'replaced' => 'warning',
                        'cancelled' => 'error',
                        default => 'neutral',
                    },
                ],
                'total_unit' => $item['total_skkni_unit'] ?? 0,
            ];
        }, $items);
    }

    protected function mapLsp(mixed $items): array
    {
        $items = $this->normalizeList($items);

        return array_map(function (array $item): array {
            $name = $item['nama'] ?? '-';

            return [
                'logo' => $item['image'] ?? null,
                'nama' => [
                    'text' => $name,
                    'url' => isset($item['id_lsp'])
                        ? route('competency.lsp.show', [
                            'lspId' => $item['id_lsp'],
                            'slug' => Str::slug($name),
                        ])
                        : null,
                ],
                'jenis' => $item['jenis'] ?? '-',
                'alamat' => $item['alamat'] ?? '-',
                'kode_kota' => $item['id_kota'] ?? '-',
                'kota' => $item['kota'] ?? '-',
                'provinsi' => $item['provinsi'] ?? '-',
                'jumlah_asesor' => $item['jumlah_asesor'] ?? 0,
                'jumlah_tuk' => $item['jumlah_tuk'] ?? 0,
                'jumlah_skema' => $item['jumlah_skema'] ?? 0,
            ];
        }, $items);
    }

    protected function mapAssessor(mixed $items): array
    {
        $items = $this->normalizeList($items);

        return array_map(function (array $item): array {
            $lspName = $item['lsp'] ?? '-';

            return [
                'nama' => $item['nama'] ?? '-',
                'kelamin' => [
                    'value' => $item['id_kelamin'] ?? null,
                    'label' => match ($item['id_kelamin'] ?? null) {
                        'L' => __('competency.gender_male'),
                        'P' => __('competency.gender_female'),
                        default => __('competency.gender_unknown'),
                    },
                ],
                'nomor_reg' => $item['nomor_reg'] ?? '-',
                'lsp' => [
                    'text' => $lspName,
                    'url' => isset($item['id_lsp'])
                        ? route('competency.lsp.show', [
                            'lspId' => $item['id_lsp'],
                            'slug' => Str::slug($lspName),
                            'tab' => 'assessor',
                        ])
                        : null,
                ],
                'umur' => $item['umur'] ?? '-',
                'pendidikan' => $item['pendidikan'] ?? '-',
                'pekerjaan' => $item['pekerjaan'] ?? '-',
                'kode_kota' => $item['id_kota'] ?? '-',
                'kota' => $item['kota'] ?? '-',
                'provinsi' => $item['provinsi'] ?? '-',
            ];
        }, $items);
    }

    protected function mapTuk(mixed $items): array
    {
        $items = $this->normalizeList($items);

        return array_map(function (array $item): array {
            $lspName = $item['lsp'] ?? '-';

            return [
                'nama' => $item['nama'] ?? '-',
                'jenis' => $item['jenis'] ?? '-',
                'lsp' => [
                    'text' => $lspName,
                    'url' => isset($item['id_lsp'])
                        ? route('competency.lsp.show', [
                            'lspId' => $item['id_lsp'],
                            'slug' => Str::slug($lspName),
                            'tab' => 'tuk',
                        ])
                        : null,
                ],
                'alamat' => $item['alamat'] ?? '-',
                'kode_kota' => $item['id_kota'] ?? '-',
                'kota' => $item['kota'] ?? '-',
                'provinsi' => $item['provinsi'] ?? '-',
                'mobile' => $item['mobile'] ?? '-',
                'telepon' => $item['telepon'] ?? '-',
                'fax' => $item['fax'] ?? '-',
                'email' => $item['email'] ?? '-',
            ];
        }, $items);
    }

    protected function mapScheme(mixed $items): array
    {
        $items = $this->normalizeList($items);

        return array_map(function (array $item): array {
            $lspName = $item['lsp'] ?? '-';
            $unitCount = (int) ($item['total_unit'] ?? 0);

            return [
                'nama' => $item['nama'] ?? '-',
                'jenis' => $item['jenis'] ?? '-',
                'kode' => $item['kode'] ?? '-',
                'lsp' => [
                    'text' => $lspName,
                    'url' => isset($item['id_lsp'])
                        ? route('competency.lsp.show', [
                            'lspId' => $item['id_lsp'],
                            'slug' => Str::slug($lspName),
                            'tab' => 'scheme',
                            'scheme' => $item['id_skema'] ?? null,
                        ])
                        : null,
                ],
                'bidang' => $item['bidang'] ?? '-',
                'subsektor' => $item['subsektor'] ?? '-',
                'total_unit' => [
                    'label' => $unitCount > 0
                        ? trans_choice('competency.unit_count_label', $unitCount, ['count' => $unitCount])
                        : __('competency.unit_count_zero'),
                    'tone' => $unitCount > 0 ? 'primary' : 'neutral',
                ],
            ];
        }, $items);
    }

    protected function normalizeList(mixed $items): array
    {
        if (!is_array($items)) {
            return [];
        }

        return array_values(array_filter($items, 'is_array'));
    }
}
