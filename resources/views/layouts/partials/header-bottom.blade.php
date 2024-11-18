<div class="flex justify-center border-b z-50">
    <div role="button" class="btn btn-ghost m-1 text-gray-500 hover:bg-transparent hover:text-blue-500">
        <a wire:navigate href="{{ route('home') }}">Home</a>
    </div>

    <div class="dropdown dropdown-hover">
        <div class="btn btn-ghost m-1 text-gray-500 hover:bg-transparent hover:text-blue-500">Profil<i class="fas fa-angle-down"></i></div>
        <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-[1] w-52 p-2 shadow">
            <li class="ml-0"><a wire:navigate href="{{ route('information.post', ['id' => 1, 'slug' => Str::slug('Tentang BDI Yogyakarta')]) }}" class="hover:bg-blue-100 hover:text-blue-500">Tentang BDI Yogyakarta</a></li>
            <li class="ml-0"><a wire:navigate href="{{ route('information.post', ['id' => 2, 'slug' => Str::slug('Visi & Misi')]) }}" class="hover:bg-blue-100 hover:text-blue-500">Visi & Misi</a></li>
            <li class="ml-0"><a wire:navigate href="{{ route('information.post', ['id' => 4, 'slug' => Str::slug('Renstra & Kebijakan')]) }}" class="hover:bg-blue-100 hover:text-blue-500">Renstra & Kebijakan</a></li>
            <li class="ml-0"><a wire:navigate href="{{ route('information.post', ['id' => 3, 'slug' => Str::slug('Tugas Pokok & Fungsi')]) }}" class="hover:bg-blue-100 hover:text-blue-500">Tugas Pokok & Fungsi</a></li>
            <li class="ml-0"><a wire:navigate href="" class="hover:bg-blue-100 hover:text-blue-500">Data Kepegawaian</a></li>
            <li class="ml-0"><a wire:navigate href="{{ route('pages.show', ['slug' => 'struktur-organisasi-bdi-yogyakarta']) }}" class="hover:bg-blue-100 hover:text-blue-500">Struktur Organisasi</a></li>
            <li class="ml-0"><a wire:navigate href="{{ route('pages.show', ['slug' => 'daftar-pejabat-pengelola-informasi-dan-dokumentasi']) }}" class="hover:bg-blue-100 hover:text-blue-500">Daftar & Penetapan PPID</a></li>
            <li class="ml-0"><a wire:navigate href="" class="hover:bg-blue-100 hover:text-blue-500">Profil Pejabat</a></li>
            <li class="ml-0"><a wire:navigate href="{{ route('pages.show', ['slug' => 'laporan-harta-kekayaan-penyelenggara-negara-lhkpn']) }}" class="hover:bg-blue-100 hover:text-blue-500">LHKPN</a></li>
        </ul>
    </div>

    <div class="dropdown dropdown-hover">
        <div class="btn btn-ghost m-1 text-gray-500 hover:bg-transparent hover:text-blue-500">Program Kerja<i class="fas fa-angle-down"></i></div>
        <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-[1] w-52 p-2 shadow">
            <li class="ml-0"><a wire:navigate href="{{ route('training.index') }}" class="hover:bg-blue-100 hover:text-blue-500">Diklat 3-in-1</a></li>
            <li class="ml-0"><a wire:navigate href="{{ route('ibiza.index') }}" class="hover:bg-blue-100 hover:text-blue-500">Inkubator Bisnis</a></li>
            <li class="ml-0"><a wire:navigate href="{{ route('competency.index') }}" class="hover:bg-blue-100 hover:text-blue-500">Infrastruktur Kompetensi</a></li>
        </ul>
    </div>

    <div class="dropdown dropdown-hover">
        <div class="btn btn-ghost m-1 text-gray-500 hover:bg-transparent hover:text-blue-500">Zona Integritas<i class="fas fa-angle-down"></i></div>
        <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-[1] w-52 p-2 shadow">
            <li class="ml-0"><a href="" class="hover:bg-blue-100 hover:text-blue-500">Maklumat Pelayanan Publik</a></li>
            <li class="ml-0"><a wire:navigate href="{{ route('gratification.index') }}" class="hover:bg-blue-100 hover:text-blue-500">Pelaporan Gratifikasi</a></li>
            <li class="ml-0"><a wire:navigate href="{{ route('wbs.index') }}" class="hover:bg-blue-100 hover:text-blue-500">Whistle Blowing System</a></li>
            <li class="ml-0"><a wire:navigate href="{{ route('information.question') }}" class="hover:bg-blue-100 hover:text-blue-500">Pengaduan Masyarakat</a></li>
            <li class="ml-0"><a href="" class="hover:bg-blue-100 hover:text-blue-500">Survei Kualitas Layanan</a></li>
        </ul>
    </div>

    <div class="dropdown dropdown-hover">
        <div class="btn btn-ghost m-1 text-gray-500 hover:bg-transparent hover:text-blue-500">Layanan Publik<i class="fas fa-angle-down"></i></div>
        <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-[1] w-52 p-2 shadow">
            <li class="ml-0"><a href="" class="hover:bg-blue-100 hover:text-blue-500">Motto & Nilai Organisasi</a></li>
            <li class="ml-0"><a href="" class="hover:bg-blue-100 hover:text-blue-500">Unit Pelayanan Publik</a></li>
            <li class="ml-0"><a href="" class="hover:bg-blue-100 hover:text-blue-500">Pengadaan Barang/Jasa</a></li>
            <li class="ml-0"><a href="" class="hover:bg-blue-100 hover:text-blue-500">Layanan Informasi Publik</a></li>
            <li class="ml-0"><a href="" class="hover:bg-blue-100 hover:text-blue-500">Pengaduan Masyarakat</a></li>
            <li class="ml-0"><a href="" class="hover:bg-blue-100 hover:text-blue-500">Kode Etik</a></li>
            <li class="ml-0"><a href="" class="hover:bg-blue-100 hover:text-blue-500">Laporan Survei Kepuasan Masyarakat</a></li>
        </ul>
    </div>

    <div class="dropdown dropdown-hover">
        <div class="btn btn-ghost m-1 text-gray-500 hover:bg-transparent hover:text-blue-500">Informasi Publik<i class="fas fa-angle-down"></i></div>
        <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-[1] w-52 p-2 shadow">
            <li class="ml-0"><a href="" class="hover:bg-blue-100 hover:text-blue-500">Berkala</a></li>
            <li class="ml-0"><a href="" class="hover:bg-blue-100 hover:text-blue-500">Setiap Saat</a></li>
            <li class="ml-0"><a href="" class="hover:bg-blue-100 hover:text-blue-500">Jawaban Pertanyaan</a></li>
            <li class="ml-0"><a href="" class="hover:bg-blue-100 hover:text-blue-500">Laporan Kinerja</a></li>
        </ul>
    </div>

    <div class="dropdown dropdown-hover">
        <div class="btn btn-ghost m-1 text-gray-500 hover:bg-transparent hover:text-blue-500">Kontak<i class="fas fa-angle-down"></i></div>
        <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-[1] w-52 p-2 shadow">
            <li class="ml-0"><a href="" class="hover:bg-blue-100 hover:text-blue-500">Pengaduan Masyarakat</a></li>
            <li class="ml-0"><a href="" class="hover:bg-blue-100 hover:text-blue-500">Form Permohonan Informasi Publik</a></li>
            <li class="ml-0"><a href="" class="hover:bg-blue-100 hover:text-blue-500">Prosedur Memperoleh Informasi</a></li>
            <li class="ml-0"><a href="" class="hover:bg-blue-100 hover:text-blue-500">Jawaban Pertanyaan</a></li>
            <li class="ml-0"><a href="" class="hover:bg-blue-100 hover:text-blue-500">Laporan Kinerja</a></li>
            <li class="ml-0"><a href="" class="hover:bg-blue-100 hover:text-blue-500">Whistle Blowing System</a></li>
        </ul>
    </div>

    <div role="button" class="btn btn-ghost m-1 text-gray-500 hover:bg-transparent hover:text-blue-500">
        <a href="#">Virtual Tour</a>
    </div>
</div>
