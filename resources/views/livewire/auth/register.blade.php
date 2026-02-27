<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
    <div class="w-full sm:max-w-2xl mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
        <h2 class="text-2xl font-bold text-center mb-6">Pendaftaran Peserta</h2>

        <form wire:submit.prevent="register">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- NIK -->
                <div>
                    <label for="nik" class="block font-medium text-sm text-gray-700">NIK (KTP)</label>
                    <input wire:model="nik" id="nik" type="text" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required autofocus />
                    @error('nik') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Nama -->
                <div>
                    <label for="name" class="block font-medium text-sm text-gray-700">Nama Lengkap</label>
                    <input wire:model="name" id="name" type="text" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required />
                    @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Tempat Lahir -->
                <div>
                    <label for="birth_place" class="block font-medium text-sm text-gray-700">Tempat Lahir</label>
                    <input wire:model="birth_place" id="birth_place" type="text" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required />
                    @error('birth_place') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Tanggal Lahir -->
                <div>
                    <label for="birth_date" class="block font-medium text-sm text-gray-700">Tanggal Lahir</label>
                    <input wire:model="birth_date" id="birth_date" type="date" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required />
                    @error('birth_date') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Jenis Kelamin -->
                <div>
                    <label for="gender_id" class="block font-medium text-sm text-gray-700">Jenis Kelamin</label>
                    <select wire:model="gender_id" id="gender_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        <option value="">Pilih...</option>
                        @foreach($genders as $gender)
                            <option value="{{ $gender->id }}">{{ $gender->type }}</option>
                        @endforeach
                    </select>
                    @error('gender_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Agama -->
                <div>
                    <label for="religion_id" class="block font-medium text-sm text-gray-700">Agama</label>
                    <select wire:model="religion_id" id="religion_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        <option value="">Pilih...</option>
                        @foreach($religions as $religion)
                            <option value="{{ $religion->id }}">{{ $religion->name }}</option>
                        @endforeach
                    </select>
                    @error('religion_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Golongan Darah -->
                <div>
                    <label for="blood_type" class="block font-medium text-sm text-gray-700">Golongan Darah</label>
                    <select wire:model="blood_type" id="blood_type" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="">Pilih (Opsional)...</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="AB">AB</option>
                        <option value="O">O</option>
                    </select>
                    @error('blood_type') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Pekerjaan -->
                <div>
                    <label for="occupation_id" class="block font-medium text-sm text-gray-700">Pekerjaan</label>
                    <select wire:model="occupation_id" id="occupation_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        <option value="">Pilih...</option>
                        @foreach($occupations as $occupation)
                            <option value="{{ $occupation->id }}">{{ $occupation->name }}</option>
                        @endforeach
                    </select>
                    @error('occupation_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
                    <input wire:model="email" id="email" type="email" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required />
                    @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- No HP -->
                <div>
                    <label for="phone" class="block font-medium text-sm text-gray-700">No HP/WA</label>
                    <input wire:model="phone" id="phone" type="text" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required />
                    @error('phone') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                </div>

            <!-- Alamat -->
            <div class="mt-4">
                <label for="address" class="block font-medium text-sm text-gray-700">Alamat Lengkap</label>
                <textarea wire:model="address" id="address" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" rows="3" required></textarea>
                @error('address') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Provinsi, Kota, Kecamatan, Kelurahan -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label for="province_id" class="block font-medium text-sm text-gray-700">Provinsi</label>
                    <select wire:model.live="province_id" id="province_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        <option value="">Pilih Provinsi...</option>
                        @foreach($provinces as $prov)
                            <option value="{{ $prov->id }}">{{ $prov->name }}</option>
                        @endforeach
                    </select>
                    @error('province_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="city_id" class="block font-medium text-sm text-gray-700">Kota / Kabupaten</label>
                    <select wire:model.live="city_id" id="city_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        <option value="">Pilih Kota/Kabupaten...</option>
                        @foreach($cities as $ct)
                            <option value="{{ $ct->id }}">{{ $ct->name }}</option>
                        @endforeach
                    </select>
                    @error('city_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label for="district_id" class="block font-medium text-sm text-gray-700">Kecamatan</label>
                    <select wire:model.live="district_id" id="district_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        <option value="">Pilih Kecamatan...</option>
                        @foreach($districts as $dt)
                            <option value="{{ $dt->id }}">{{ $dt->name }}</option>
                        @endforeach
                    </select>
                    @error('district_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="village_id" class="block font-medium text-sm text-gray-700">Kelurahan / Desa</label>
                    <select wire:model="village_id" id="village_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        <option value="">Pilih Kelurahan/Desa...</option>
                        @foreach($villages as $vl)
                            <option value="{{ $vl->id }}">{{ $vl->name }}</option>
                        @endforeach
                    </select>
                    @error('village_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('participant.login') }}">
                    Sudah punya akun? Login
                </a>

                <button type="submit" class="ml-4 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Daftar
                </button>
            </div>
        </form>
    </div>
</div>
