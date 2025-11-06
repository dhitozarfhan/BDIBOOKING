<div>
    <div class="p-4 sm:p-8 bg-base-100 shadow sm:rounded-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-sm breadcrumbs">
                <ul>
                    <li><a href="{{ route('home') }}"><i class="bi bi-house-fill"></i></a></li>
                    <li><a href="{{ route('gratification') }}">Pelaporan Gratifikasi</a></li>
                    <li><a href="{{ route('gratification.status') }}">Status Laporan</a></li>
                    <li>Respon Laporan</li>
                </ul>
            </div>
            <h2 class="text-2xl font-bold text-base-content mt-4">
                Respon Laporan Gratifikasi
            </h2>


            @if($reportDetail)
                <div class="mt-6">
                    <div class="bg-base-100 rounded-xl shadow-md border border-base-300 overflow-hidden">
                        <table class="table table-zebra w-full">
                            <tbody>
                                <tr>
                                    <th class="w-1/3 bg-base-200 font-semibold text-base-content">
                                        <i class="bi bi-chat-left-text mr-2 text-primary"></i>Subjek Laporan
                                    </th>
                                    <td class="font-medium">{{ $reportDetail->subject }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-base-200 font-semibold text-base-content">
                                        <i class="bi bi-person mr-2 text-primary"></i>Nama Pelapor
                                    </th>
                                    <td>{{ $reportDetail->name }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-base-200 font-semibold text-base-content">
                                        <i class="bi bi-telephone mr-2 text-primary"></i>Telepon Pelapor
                                    </th>
                                    <td>{{ $reportDetail->mobile }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-base-200 font-semibold text-base-content">
                                        <i class="bi bi-calendar-event mr-2 text-primary"></i>Tanggal Laporan
                                    </th>
                                    <td>{{ $reportDetail->time_insert ? $reportDetail->time_insert->format('d/m/Y H:i') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-base-200 font-semibold text-base-content">
                                        <i class="bi bi-flag mr-2 text-primary"></i>Status Laporan
                                    </th>
                                    <td>
                                        @if($reportDetail->status == 'I')
                                            <span class="badge badge-warning gap-2">
                                                <i class="bi bi-hourglass-split"></i>Inisiasi
                                            </span>
                                        @elseif($reportDetail->status == 'P')
                                            <span class="badge badge-info gap-2">
                                                <i class="bi bi-arrow-repeat"></i>Proses
                                            </span>
                                        @elseif($reportDetail->status == 'D')
                                            <span class="badge badge-primary gap-2">
                                                <i class="bi bi-send-check"></i>Disposisi
                                            </span>
                                        @elseif($reportDetail->status == 'T')
                                            <span class="badge badge-success gap-2">
                                                <i class="bi bi-check-circle"></i>Selesai
                                            </span>
                                        @else
                                            <span class="badge badge-ghost gap-2">
                                                <i class="bi bi-question-circle"></i>Tidak Dikenal
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-base-200 font-semibold text-base-content align-top">
                                        <i class="bi bi-file-text mr-2 text-primary"></i>Isi Laporan
                                    </th>
                                    <td class="whitespace-pre-wrap">{{ $reportDetail->content }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-base-200 font-semibold text-base-content align-top">
                                        <i class="bi bi-reply mr-2 text-secondary"></i>Jawaban
                                    </th>
                                    <td class="whitespace-pre-wrap">
                                        @if($reportDetail->status == 'T')
                                            <div class="bg-success/10 border-l-4 border-success p-3 rounded">
                                                {{ $reportDetail->answer ?? 'Jawaban tidak tersedia.' }}
                                            </div>
                                        @else
                                            <div class="bg-base-200 border-l-4 border-base-300 p-3 rounded text-base-content/60 italic">
                                                Belum terdapat tanggapan resmi atas laporan ini.
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                                @if($reportDetail->status === 'T' && $reportDetail->attachment)
                                    <tr>
                                        <th class="bg-base-200 font-semibold text-base-content">
                                            <i class="bi bi-paperclip mr-2 text-secondary"></i>Lampiran Jawaban
                                        </th>
                                        <td>
                                            <a href="{{ Storage::url($reportDetail->attachment) }}" target="_blank" class="btn btn-sm btn-outline btn-primary gap-2">
                                                <i class="bi bi-download"></i>
                                                {{ basename($reportDetail->attachment) }}
                                            </a>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6">
                        <a href="{{ route('gratification.status') }}" class="btn btn-ghost btn-outline" wire:navigate>
                           Kembali
                        </a>
                    </div>
                </div>
            @elseif($statusError)
                <div class="mt-4 alert alert-error shadow-lg">
                    <div>
                        <span>{{ $statusError }}</span>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
