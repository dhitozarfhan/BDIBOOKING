<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
    {{-- Back Button --}}
    <div class="mb-8">
        <a href="{{ route('pnbp.detail', ['id_diklat' => $training->id, 'slug' => Str::slug($training->title)]) }}" wire:navigate class="inline-flex items-center gap-2 text-sm font-medium text-base-content/60 hover:text-primary transition-colors duration-200 group">
            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Detail Diklat
        </a>
    </div>

    {{-- Hero Header --}}
    <div class="bg-base-100 rounded-3xl border border-base-200/80 shadow-sm overflow-hidden mb-8">
        <div class="relative">
            {{-- Banner Image --}}
            <div class="relative h-48 md:h-56 overflow-hidden">
                <img src="{{ $training->image ? Storage::url($training->image) : asset('images/default-training.jpg') }}" alt="{{ $training->title }}" class="w-full h-full object-cover" />
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>
                {{-- Badge on image --}}
                <div class="absolute top-4 left-4">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/20 backdrop-blur-md text-white text-xs font-semibold uppercase tracking-wider border border-white/20">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        Diklat PNBP
                    </span>
                </div>
            </div>

            {{-- Info Overlay --}}
            <div class="relative -mt-16 px-6 md:px-8 pb-6 md:pb-8">
                <div class="bg-base-100 rounded-2xl border border-base-200/80 shadow-lg p-5 md:p-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <h1 class="text-xl md:text-2xl font-bold text-base-content leading-tight mb-2">
                                {{ $training->title }}
                            </h1>
                            <p class="text-sm text-base-content/50 leading-relaxed">
                                Silakan lengkapi formulir dan persyaratan di bawah ini untuk melanjutkan pendaftaran.
                            </p>
                        </div>
                        <div class="shrink-0 flex flex-col items-start md:items-end gap-1">
                            <span class="text-xs font-semibold uppercase tracking-wider text-base-content/35">Biaya Pendaftaran</span>
                            <span class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                                {{ 'Rp ' . number_format($training->price, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="bg-base-100 rounded-3xl border border-base-200/80 shadow-sm overflow-hidden">
        <form wire:submit.prevent="submit">
            {{-- Section Header --}}
            <div class="px-6 md:px-8 pt-6 md:pt-8 pb-4">
                <div class="flex items-center gap-3 mb-1">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500/15 to-purple-500/15 flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-base-content">Persyaratan Dokumen</h2>
                        <p class="text-xs text-base-content/40">Upload dokumen yang diperlukan</p>
                    </div>
                </div>
            </div>

            <div class="px-6 md:px-8 pb-8">
                @if($training->requiredFields->isEmpty())
                    <div class="rounded-2xl bg-emerald-50/80 border border-emerald-100 p-5 flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl bg-emerald-100 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-emerald-800">Tidak ada persyaratan khusus</p>
                            <p class="text-xs text-emerald-600/80 mt-0.5">Anda dapat langsung mendaftar tanpa upload dokumen tambahan.</p>
                        </div>
                    </div>
                @else
                    <div class="space-y-5">
                        @foreach($training->requiredFields as $index => $field)
                            <div class="group">
                                <label class="block mb-2">
                                    <span class="text-sm font-semibold text-base-content">{{ $field->name }} <span class="text-red-400">*</span></span>
                                </label>

                                @if($field->is_file)
                                    <label for="dropzone-file-{{ $field->id }}" class="relative block w-full cursor-pointer">
                                        <div class="rounded-2xl border-2 border-dashed @error('formData.'.$field->id) border-red-300 bg-red-50/30 @else border-base-300/80 bg-base-200/20 hover:border-indigo-400 hover:bg-indigo-50/30 @enderror transition-all duration-200 p-6">
                                            <div class="flex flex-col items-center justify-center text-center">
                                                @if(isset($formData[$field->id]) && $formData[$field->id] instanceof \Illuminate\Http\UploadedFile)
                                                    {{-- File uploaded state --}}
                                                    <div class="w-12 h-12 rounded-2xl bg-emerald-100 flex items-center justify-center mb-3">
                                                        <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </div>
                                                    <p class="text-sm font-semibold text-emerald-700 mb-0.5">File siap diupload</p>
                                                    <p class="text-xs text-base-content/50 truncate max-w-xs">{{ $formData[$field->id]->getClientOriginalName() }}</p>
                                                @else
                                                    {{-- Loading state --}}
                                                    <div wire:loading wire:target="formData.{{ $field->id }}" class="flex flex-col items-center">
                                                        <span class="loading loading-spinner loading-md text-indigo-500 mb-3"></span>
                                                        <p class="text-sm font-medium text-base-content/60">Mengupload...</p>
                                                    </div>
                                                    {{-- Default state --}}
                                                    <div wire:loading.remove wire:target="formData.{{ $field->id }}" class="flex flex-col items-center">
                                                        <div class="w-12 h-12 rounded-2xl bg-base-200/60 flex items-center justify-center mb-3 group-hover:bg-indigo-100 transition-colors">
                                                            <svg class="w-6 h-6 text-base-content/30 group-hover:text-indigo-500 transition-colors" fill="none" viewBox="0 0 20 16" xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                                            </svg>
                                                        </div>
                                                        <p class="text-sm text-base-content/60 mb-1">
                                                            <span class="font-semibold text-indigo-600 hover:text-indigo-700">Klik untuk upload</span> atau drag and drop
                                                        </p>
                                                        <p class="text-xs text-base-content/35">PDF, PNG, JPG, DOC — Maks. 5MB</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <input wire:model.live="formData.{{ $field->id }}" id="dropzone-file-{{ $field->id }}" type="file" class="hidden" accept=".pdf,.png,.jpg,.jpeg,.doc,.docx" />
                                    </label>
                                @else
                                    <input wire:model.defer="formData.{{ $field->id }}" type="text" placeholder="Masukkan {{ strtolower($field->name) }}" class="input input-bordered w-full rounded-xl focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @error('formData.'.$field->id) input-error @enderror" />
                                @endif

                                @error('formData.'.$field->id)
                                    <p class="flex items-center gap-1 mt-2 text-xs text-red-500">
                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.268 16.5c-.77.833.192 2.5 1.732 2.5z" /></svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Action Buttons --}}
                <div class="mt-10 pt-8 border-t border-base-200/80 flex flex-col sm:flex-row items-center justify-end gap-3 pb-4">
                    <a href="{{ route('pnbp.detail', ['id_diklat' => $training->id, 'slug' => Str::slug($training->title)]) }}" wire:navigate class="btn btn-ghost btn-lg rounded-xl w-full sm:w-auto order-2 sm:order-1 text-base-content/60 hover:text-base-content">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-lg bg-indigo-600 hover:bg-indigo-700 border-0 text-white rounded-xl px-10 w-full sm:w-auto order-1 sm:order-2 shadow-lg hover:shadow-xl transition-all duration-200" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="submit" class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            Selesaikan Pendaftaran
                        </span>
                        <span wire:loading wire:target="submit" class="flex items-center gap-2">
                            <span class="loading loading-spinner loading-sm"></span>
                            Memproses...
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
