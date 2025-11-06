<div class="card bg-base-100 shadow-xl border border-base-200">
    <div class="card-body space-y-4">
        <div class="flex items-center justify-between flex-wrap gap-3">
            <div>
                <h2 class="card-title">Tanda Tangan Peserta</h2>
                <p class="text-sm text-base-content/70">Mohon bubuhkan tanda tangan digital sebagai persetujuan data.</p>
            </div>
        </div>

        <div class="space-y-3" x-data="signaturePadComponent()" x-init="init()">
            <div
                class="rounded-2xl border border-dashed border-base-300/90 bg-base-200/60 p-4 shadow-inner"
                wire:ignore
            >
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2 text-xs sm:text-sm text-base-content/60">
                        <span>Gunakan kursor atau layar sentuh untuk menandatangani.</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" class="btn btn-warning btn-xs" x-on:click.prevent="undo()">
                            Undo
                        </button>
                        <button type="button" class="btn btn-outline btn-xs btn-error" x-on:click.prevent="clear()">
                            Bersihkan
                        </button>
                    </div>
                </div>
                <div class="relative">
                    <canvas
                        x-ref="canvas"
                        class="w-full h-48 rounded-xl bg-base-100 shadow-sm"
                        style="touch-action: none;"
                    ></canvas>
                    <div class="pointer-events-none absolute inset-0 rounded-xl border-2 border-dashed border-base-300/80"></div>
                </div>
            </div>
            <input type="hidden" x-ref="hidden" name="ttd" wire:model.defer="ttd" />
        </div>
        @error('ttd') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
    </div>
</div>
