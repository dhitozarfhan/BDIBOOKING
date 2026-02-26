<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8">
    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ route('pnbp.detail', ['id_diklat' => $training->id, 'slug' => Str::slug($training->title)]) }}" wire:navigate class="btn btn-ghost btn-sm hover:bg-base-200/50 gap-1 text-base-content/70">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Detail Diklat
        </a>
    </div>

    {{-- Header --}}
    <div class="card bg-base-100 shadow-lg border border-base-200 mb-8">
        <div class="card-body p-6 flex flex-col md:flex-row items-center gap-6">
            <div class="w-full md:w-1/3 shrink-0">
                <img src="{{ $training->image ? Storage::url($training->image) : asset('images/default-training.jpg') }}" alt="{{ $training->title }}" class="rounded-xl w-full h-32 object-cover shadow-sm" />
            </div>
            <div class="w-full md:w-2/3">
                <div class="inline-flex items-center gap-2 px-3 py-1 mb-2 rounded-full bg-secondary/20 text-secondary text-xs font-semibold uppercase tracking-wide">
                    Diklat PNBP
                </div>
                <h1 class="text-2xl font-bold leading-tight text-base-content mb-2">
                    Daftar: {{ $training->title }}
                </h1>
                <p class="text-sm text-base-content/60">
                    Silakan lengkapi formulir dan persyaratan di bawah ini untuk melanjutkan pendaftaran.
                </p>
                <div class="mt-2 text-primary font-bold">
                    {{ 'Rp ' . number_format($training->price, 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>

    {{-- Form --}}
    <div class="card bg-base-100 shadow-xl border border-base-200">
        <form wire:submit.prevent="submit" class="card-body p-6 md:p-8">
            <h2 class="text-xl font-bold mb-6 flex items-center gap-2">
                <svg class="w-6 h-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Persyaratan Dokumen
            </h2>

            @if($training->requiredFields->isEmpty())
                <div class="alert bg-base-200/50 border-none mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-info shrink-0 w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Tidak ada persyaratan dokumen khusus untuk diklat ini. Anda dapat langsung mendaftar.</span>
                </div>
            @else
                <div class="grid grid-cols-1 gap-6 mb-8">
                    @foreach($training->requiredFields as $field)
                        <div class="form-control w-full">
                            <label class="label pt-0">
                                <span class="label-text font-semibold text-base-content">{{ $field->name }} <span class="text-error">*</span></span>
                            </label>

                            @if($field->is_file)
                                <div class="flex items-center justify-center w-full">
                                    <label for="dropzone-file-{{ $field->id }}" class="flex flex-col items-center justify-center w-full h-32 border-2 @error('formData.'.$field->id) border-error @else border-base-300 @enderror border-dashed rounded-xl cursor-pointer bg-base-200/30 hover:bg-base-200 transition-colors">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            @if(isset($formData[$field->id]) && $formData[$field->id] instanceof \Illuminate\Http\UploadedFile)
                                                <svg class="w-8 h-8 mb-3 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <p class="mb-2 text-sm text-base-content/70 font-semibold truncate px-4 max-w-full"><span class="font-semibold text-success">File siap diupload:</span> {{ $formData[$field->id]->getClientOriginalName() }}</p>
                                            @else
                                                <div wire:loading wire:target="formData.{{ $field->id }}">
                                                    <span class="loading loading-spinner loading-md text-primary mb-3"></span>
                                                    <p class="mb-2 text-sm text-base-content/70 font-semibold">Mengupload...</p>
                                                </div>
                                                <div wire:loading.remove wire:target="formData.{{ $field->id }}" class="flex flex-col items-center">
                                                    <svg class="w-8 h-8 mb-3 text-base-content/40" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                                    </svg>
                                                    <p class="mb-2 text-sm text-base-content/70"><span class="font-semibold text-primary">Klik untuk upload file</span> atau drag and drop</p>
                                                    <p class="text-xs text-base-content/50">Maks. 5MB</p>
                                                </div>
                                            @endif
                                        </div>
                                        <input wire:model.live="formData.{{ $field->id }}" id="dropzone-file-{{ $field->id }}" type="file" class="hidden" accept=".pdf,.png,.jpg,.jpeg,.doc,.docx" />
                                    </label>
                                </div>
                            @else
                                <input wire:model.defer="formData.{{ $field->id }}" type="text" placeholder="Masukkan {{ strtolower($field->name) }}" class="input input-bordered w-full @error('formData.'.$field->id) input-error @enderror" />
                            @endif

                            @error('formData.'.$field->id)
                                <label class="label pb-0">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="divider mt-0 mb-6"></div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('pnbp.detail', ['id_diklat' => $training->id, 'slug' => Str::slug($training->title)]) }}" wire:navigate class="btn btn-outline rounded-xl">Batal</a>
                <button type="submit" class="btn btn-primary rounded-xl px-8" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="submit">Selesaikan Pendaftaran</span>
                    <span wire:loading wire:target="submit">
                        <span class="loading loading-spinner loading-sm"></span>
                        Memproses...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
