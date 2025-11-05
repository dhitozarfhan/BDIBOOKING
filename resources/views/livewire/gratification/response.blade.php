<div>
    <div class="p-4 sm:p-8 bg-base-100 shadow sm:rounded-lg mt-4 pb-10">
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
                Status Laporan Gratifikasi
            </h2>

            @if($reportDetail)
                <div class="mt-8 card bg-base-200 shadow-xl">
                    <div class="card-header border-b border-base-300 px-6 py-4">
                        <h3 class="card-title">Respon Laporan Gratifikasi</h3>
                    </div>
                    <div class="card-body">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                            <div class="form-control">
                                <label class="label"><span class="label-text font-semibold">Subjek Laporan</span></label>
                                <p class="prose min-w-full break-words">{{ $reportDetail->subject }}</p>
                            </div>
                            <div class="form-control">
                                <label class="label"><span class="label-text font-semibold">Telepon Pelapor</span></label>
                                <p class="prose min-w-full break-words">{{ $reportDetail->mobile }}</p>
                            </div>
                            <div class="form-control">
                                <label class="label"><span class="label-text font-semibold">Nama Pelapor</span></label>
                                <p class="prose min-w-full break-words">{{ $reportDetail->name }}</p>
                            </div>
                            <div class="form-control">
                                <label class="label"><span class="label-text font-semibold">Tanggal Laporan</span></label>
                                <p class="prose min-w-full break-words">{{ $reportDetail->time_insert ? $reportDetail->time_insert->format('d/m/Y H:i') : 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="mt-4 form-control">
                            <label class="label"><span class="label-text font-semibold">Status Laporan</span></label>
                            <p class="prose min-w-full break-words">
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
                            </p>
                        </div>

                        <div class="mt-4 form-control">
                            <label class="label"><span class="label-text font-semibold">Isi Laporan</span></label>
                            <p class="prose min-w-full break-words">{{ $reportDetail->content }}</p>
                        </div>

                        @if($reportDetail->status === 'T')
                            <div class="mt-4 border-t border-base-300 pt-4">
                                <div class="form-control">
                                    <label class="label"><span class="label-text font-semibold">Jawaban</span></label>
                                    <p class="prose min-w-full break-words">{{ $reportDetail->answer ?? 'Jawaban tidak tersedia.' }}</p>
                                </div>

                                @if($reportDetail->attachment)
                                    <div class="mt-4 form-control">
                                        <label class="label"><span class="label-text font-semibold">Lampiran Jawaban</span></label>
                                        <a href="{{ Storage::url($reportDetail->attachment) }}" target="_blank" class="link link-primary">
                                            <i class="bi bi-paperclip"></i> {{ basename($reportDetail->attachment) }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                    <div class="card-footer border-t border-base-300 px-6 py-4">
                        <a href="{{ route('gratification.status') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
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
