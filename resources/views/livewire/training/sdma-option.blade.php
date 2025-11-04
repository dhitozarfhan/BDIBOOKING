<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8">
    <div class="hero rounded-2xl bg-gradient-to-br from-primary/10 via-secondary/10 to-primary/5 py-10 shadow-lg border border-base-200">
        <div class="hero-content text-center">
            <div class="max-w-xl">
                <h1 class="text-3xl md:text-4xl font-bold leading-tight text-base-content">Pendaftaran Diklat SDM Aparatur</h1>
                <p class="py-6">Silakan pilih asal pendaftar untuk melanjutkan.</p>
                <div class="flex justify-center gap-4">
                    <a href="{{ route('training.kemenperin-register', ['id_diklat' => $id_diklat, 'slug' => $slug]) }}" wire:navigate class="btn btn-primary btn-disabled">Satker Kemenperin</a>
                    <a href="{{ route('training.register', ['id_diklat' => $id_diklat, 'slug' => $slug]) }}" wire:navigate class="btn btn-secondary btn-disabled">Luar Kemenperin</a>
                </div>
            </div>
        </div>
    </div>
</div>
