<x-app-layout>
    <section class="bg-gray-100 py-10">
        @if (session('success'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 7000)" x-show="show"
                class="fixed inset-0 bg-black bg-opacity-30 backdrop-blur-sm flex items-center justify-center z-40">
                <div class="fixed z-50 max-w-sm w-full bg-white rounded-3xl shadow-lg p-8 text-center space-y-6 font-sans font-medium select-none">
                    <div class="mx-auto w-16 h-16 rounded-full bg-green-100 flex items-center justify-center">
                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                            <path d="M20 6L9 17l-5-5"/>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-extrabold text-gray-900">Pembelian Berhasil</h2>
                    <p class="text-gray-600 text-base">{{ session('success') }}</p>
                    <button @click="show = false" class="inline-flex items-center px-6 py-3 font-semibold rounded-full bg-gray-900 text-white hover:bg-gray-800 transition-transform transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-gray-300">
                        Tutup
                    </button>
                </div>
            </div>
        @endif
        <form action="{{ route('participant.store', $seminar->seminar_id) }}" method="POST" class="flex flex-col gap-6">
            @csrf
            <div class="container mx-auto max-w-6xl flex flex-col lg:flex-row gap-10">
                <!-- Left Side -->
                <div class="flex-1">
                    <h2 class="text-2xl font-bold mb-6 text-gray-800">Detail Pembelian</h2>

                    <div class="bg-slate-200 rounded-xl flex items-center gap-4 px-6 py-4 mb-10 max-w-lg">
                        <img src="{{ $seminar->getThumbnailImage() }}" class="h-20 w-20 object-cover rounded-lg flex-shrink-0" alt="Thumbnail">
                        <div class="flex flex-col truncate">
                            <p class="text-lg font-semibold text-gray-900 truncate max-w-xs">{{ $seminar->title }}</p>
                            <p class="text-lg font-medium">Rp {{ number_format($seminar->price, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <hr class="mb-10 max-w-lg border-gray-300">

                    <div class="max-w-lg">
                        <h3 class="text-xl font-semibold mb-4 text-gray-800">Info Kontak</h3>
                        <div class="flex flex-col gap-1">
                            <label class="font-medium text-gray-700">Nama Lengkap</label>
                            <input type="text" class="border border-gray-400 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#064E40]" placeholder="Masukkan nama Anda">
                        </div>

                        <div class="flex flex-col gap-1">
                            <label class="font-medium text-gray-700">Nomor WhatsApp</label>
                            <input type="text" class="border border-gray-400 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#064E40]" placeholder="6281xxxxxxx">
                            <p class="text-sm text-gray-500 leading-tight">
                                Masukkan nomor dengan kode negara, misalnya: 6281xxxxxxx
                            </p>
                        </div>

                        <div class="flex flex-col gap-1">
                            <label class="font-medium text-gray-700">Email Aktif</label>
                            <input type="email" class="border border-gray-400 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#064E40]" placeholder="email@example.com">
                        </div>
                    </div>
                </div>

                <!-- Right Side -->
                <div class="flex-1 max-w-lg bg-white rounded-2xl p-8 shadow-lg self-start sticky top-44">
                    <h3 class="text-2xl font-semibold text-gray-700 mb-6">Ringkasan Pembayaran</h3>

                    <hr class="mb-6 border-gray-300">

                    <div class="flex justify-between items-center mb-6">
                        <p class="text-lg text-gray-800">Total Pembayaran</p>
                        <p class="text-lg font-semibold">Rp {{ number_format($seminar->price, 0, ',', '.') }}</p>
                    </div>

                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white text-lg font-semibold py-3 rounded-xl transition">
                        Selesaikan Pembayaran
                    </button>
                </div>
            </div>
        </form>
    </section>
</x-app-layout>
