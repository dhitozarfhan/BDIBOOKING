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
                <div class="mt-4">
                    <div class="overflow-x-auto">
                        <table class="table w-full">
                            <tbody>
                                <tr>
                                    <th class="w-1/3">Subjek Laporan</th>
                                    <td>{{ $reportDetail->subject }}</td>
                                </tr>
                                <tr>
                                    <th>Nama Pelapor</th>
                                    <td>{{ $reportDetail->name }}</td>
                                </tr>
                                <tr>
                                    <th>Telepon Pelapor</th>
                                    <td>{{ $reportDetail->mobile }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Laporan</th>
                                    <td>{{ $reportDetail->time_insert ? $reportDetail->time_insert->format('d/m/Y H:i') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Status Laporan</th>
                                    <td>
                                        @if($reportDetail->status == 'I')
                                            <span class="badge badge-warning">Inisiasi</span>
                                        @elseif($reportDetail->status == 'P')
                                            <span class="badge badge-info">Proses</span>
                                        @elseif($reportDetail->status == 'D')
                                            <span class="badge badge-primary">Disposisi</span>
                                        @elseif($reportDetail->status == 'T')
                                            <span class="badge badge-success">Selesai</span>
                                        @else
                                            <span class="badge badge-ghost">Tidak Dikenal</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Isi Laporan</th>
                                    <td>{{ $reportDetail->content }}</td>
                                </tr>
                                @if($reportDetail->status === 'T')
                                    <tr>
                                        <th>Jawaban</th>
                                        <td>{{ $reportDetail->answer ?? 'Jawaban tidak tersedia.' }}</td>
                                    </tr>
                                    @if($reportDetail->attachment)
                                        <tr>
                                            <th>Lampiran Jawaban</th>
                                            <td>
                                                <a href="{{ Storage::url($reportDetail->attachment) }}" target="_blank" class="link link-primary">
                                                    <i class="bi bi-paperclip"></i> {{ basename($reportDetail->attachment) }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endif
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('gratification.status') }}" class="btn btn-ghost">
                              Kembali
                        </a>
                    </div>
                </div>
            @elseif($statusError)
                <div class="mt-4 alert alert-error shadow-lg">
                    <div>
                        <i class="bi bi-x-circle-fill"></i>
                        <span>{{ $statusError }}</span>
                    </div>
                </div>
                <div class="mt-4">
                     <a href="{{ route('gratification.status') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
