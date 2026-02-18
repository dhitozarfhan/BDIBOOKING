<?php

namespace App\Livewire\Auth;

use Livewire\Component;

use App\Models\Participant;
use App\Models\Gender;
use App\Models\Religion;
use App\Models\Occupation;
use App\Mail\ParticipantRegistered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class Register extends Component
{
    public $nik;
    public $name;
    public $email;
    public $birth_place;
    public $birth_date;
    public $gender_id;
    public $religion_id;
    public $blood_type;
    public $phone;
    public $address;
    public $province;
    public $city;
    public $occupation_id;
    public $institution;

    public function mount()
    {
        if (session()->has('ocr_data')) {
            $ocrData = session('ocr_data');
            
            // Helper to title case
            $toTitleCase = fn($value) => ucwords(strtolower($value));

            // Map OCR text fields
            $this->nik = $ocrData['nik'] ?? $this->nik;
            $this->name = isset($ocrData['nama']) ? $toTitleCase($ocrData['nama']) : $this->name;
            
            // Map Province & City (New API)
            if (isset($ocrData['provinsi'])) {
                $rawProv = strtoupper(trim($ocrData['provinsi']));
                // Manual fix for concatenated provinces (common ones)
                $provMap = [
                    'DAERAHISTIMEWAYOGYAKARTA' => 'Daerah Istimewa Yogyakarta',
                    'DKIJAKARTA' => 'DKI Jakarta',
                    'JAWATENGAH' => 'Jawa Tengah',
                    'JAWABARAT' => 'Jawa Barat',
                    'JAWATIMUR' => 'Jawa Timur',
                    'NUSATENGGARABARAT' => 'Nusa Tenggara Barat',
                    'NUSATENGGARATIMUR' => 'Nusa Tenggara Timur',
                    'SUMATERAUTARA' => 'Sumatera Utara',
                    'SUMATERABARAT' => 'Sumatera Barat',
                    'SUMATERASELATAN' => 'Sumatera Selatan',
                    'SULAWESISELATAN' => 'Sulawesi Selatan',
                    'SULAWESIUTARA' => 'Sulawesi Utara',
                    'SULAWESITENGAH' => 'Sulawesi Tengah',
                    'SULAWESITENGGARA' => 'Sulawesi Tenggara',
                    'KALIMANTANBARAT' => 'Kalimantan Barat',
                    'KALIMANTANTIMUR' => 'Kalimantan Timur',
                    'KALIMANTANSELATAN' => 'Kalimantan Selatan',
                    'KALIMANTANTENGAH' => 'Kalimantan Tengah',
                    'KALIMANTANUTARA' => 'Kalimantan Utara',
                ];
                $this->province = $provMap[$rawProv] ?? $toTitleCase($rawProv);
            }

            if (isset($ocrData['kab_kota'])) {
                $this->city = $toTitleCase($ocrData['kab_kota']);
            }

            // Parse "ttl" (New API returns "GUNUNGKIDUL, 28-06-2003")
            if (!empty($ocrData['ttl'])) {
                $parts = explode(',', $ocrData['ttl']);
                if (count($parts) >= 1) {
                    $this->birth_place = $toTitleCase(trim($parts[0]));
                }
                if (count($parts) >= 2) {
                    $dateStr = trim($parts[1]);
                    try {
                        $date = \DateTime::createFromFormat('d-m-Y', $dateStr);
                        if ($date) {
                            $this->birth_date = $date->format('Y-m-d');
                        }
                    } catch (\Exception $e) { }
                }
            }

            // Bangun alamat lengkap dari OCR (alamat + RT/RW + Kel/Desa + Kecamatan)
            $addressParts = [];
            if (!empty($ocrData['alamat'])) $addressParts[] = $ocrData['alamat'];
            if (!empty($ocrData['rt_rw'])) $addressParts[] = 'RT/RW ' . $ocrData['rt_rw'];
            if (!empty($ocrData['kel_desa'])) $addressParts[] = 'Kel/Desa ' . $ocrData['kel_desa'];
            if (!empty($ocrData['kecamatan'])) $addressParts[] = 'Kec. ' . $ocrData['kecamatan'];
            $this->address = !empty($addressParts) ? implode(', ', $addressParts) : $this->address;

            // Map golongan darah → blood_type (abaikan jika "-" atau tidak valid)
            if (!empty($ocrData['gol_darah']) && $ocrData['gol_darah'] !== '-' && in_array(strtoupper($ocrData['gol_darah']), ['A', 'B', 'AB', 'O'])) {
                $this->blood_type = strtoupper($ocrData['gol_darah']);
            }
            
            // Map jenis kelamin → gender_id
            if (!empty($ocrData['jenis_kelamin'])) {
                $gender = Gender::whereRaw('LOWER(type) = ?', [strtolower($ocrData['jenis_kelamin'])])->first();
                if ($gender) {
                    $this->gender_id = $gender->id;
                }
            }

            // Map agama → religion_id
            if (!empty($ocrData['agama'])) {
                $religion = Religion::whereRaw('LOWER(name) = ?', [strtolower($ocrData['agama'])])->first();
                if ($religion) {
                    $this->religion_id = $religion->id;
                }
            }

            // Map pekerjaan → occupation_id (fallback ke "Lainnya" jika tidak ditemukan)
            if (!empty($ocrData['pekerjaan'])) {
                $occupation = Occupation::whereRaw('LOWER(name) = ?', [strtolower($ocrData['pekerjaan'])])->first();
                if ($occupation) {
                    $this->occupation_id = $occupation->id;
                } else {
                    $lainnya = Occupation::whereRaw('LOWER(name) = ?', ['lainnya'])->first();
                    if ($lainnya) {
                        $this->occupation_id = $lainnya->id;
                    }
                }
            }
            
            // Fallback: Parsing NIK untuk Tanggal Lahir & Jenis Kelamin jika kosong
            if (!empty($ocrData['nik']) && strlen($ocrData['nik']) == 16) {
                $nik = $ocrData['nik'];
                $tgl = (int) substr($nik, 6, 2);
                $bln = (int) substr($nik, 8, 2);
                $thn = (int) substr($nik, 10, 2);

                // Determine Gender from NIK (Date > 40 is Female)
                $genderType = 'LAKI-LAKI';
                if ($tgl > 40) {
                    $tgl -= 40;
                    $genderType = 'PEREMPUAN';
                }

                // Set Gender if not already set by OCR
                if (empty($this->gender_id)) {
                    $gender = Gender::whereRaw('LOWER(type) = ?', [strtolower($genderType)])->first();
                    if ($gender) {
                        $this->gender_id = $gender->id;
                    }
                }

                // Determine Full Year (Simple heuristic)
                // If 2-digit year is greater than current year (e.g. 99 > 26), 1999. Else 20xx.
                // NOTE: This logic assumes 17+ usually. 
                // Adjust cutoff carefully. Let's say max age 100?
                // Actually, standard is: if > current_year_short, 19xx. Else 20xx.
                $currentYearShort = (int) date('y');
                $fullYear = ($thn > $currentYearShort) ? '19' . str_pad($thn, 2, '0', STR_PAD_LEFT) : '20' . str_pad($thn, 2, '0', STR_PAD_LEFT);

                // Set Birth Date if not set and valid
                if (empty($this->birth_date) && checkdate($bln, $tgl, $fullYear)) {
                    $this->birth_date = "$fullYear-" . str_pad($bln, 2, '0', STR_PAD_LEFT) . "-" . str_pad($tgl, 2, '0', STR_PAD_LEFT);
                }
            }
            
            session()->flash('message', 'Formulir telah diisi otomatis dari hasil scan KTP.');
        }
    }

    protected function rules()
    {
        return [
            'nik' => ['required', 'string', 'max:16', 'unique:participants'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:participants'],
            'birth_place' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'date'],
            'gender_id' => ['required', 'exists:genders,id'],
            'religion_id' => ['required', 'exists:religions,id'],
            'blood_type' => ['nullable', 'string', 'in:A,B,AB,O'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string'],
            'province' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'occupation_id' => ['required', 'exists:occupations,id'],
            'institution' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function register()
    {
        $this->validate();

        // Auto-generate password
        $plainPassword = Str::random(8);

        $participant = Participant::create([
            'nik' => $this->nik,
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($plainPassword),
            'birth_place' => $this->birth_place,
            'birth_date' => $this->birth_date,
            'gender_id' => $this->gender_id,
            'religion_id' => $this->religion_id,
            'blood_type' => $this->blood_type,
            'phone' => $this->phone,
            'address' => $this->address,
            'province' => $this->province,
            'city' => $this->city,
            'occupation_id' => $this->occupation_id,
            'institution' => $this->institution,
        ]);

        // Send password via email
        try {
            \Illuminate\Support\Facades\Log::info('Mencoba mengirim email password ke: ' . $participant->email);
            
            Mail::to($participant->email)->send(
                new ParticipantRegistered($participant->name, $plainPassword)
            );
            
            \Illuminate\Support\Facades\Log::info('Email password berhasil dikirim ke: ' . $participant->email);
            session()->flash('success', 'Pendaftaran berhasil! Password telah dikirim ke email Anda. Silakan cek Inbox atau Spam.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('GAGAL mengirim email password ke ' . $participant->email . '. Error: ' . $e->getMessage());
            
            // Tampilkan pesan error ke user agar mereka sadar
            session()->flash('warning', 'Pendaftaran berhasil, namun GAGAL mengirim email password. Silakan hubungi admin atau coba "Lupa Password". Error: ' . $e->getMessage());
        }

        return redirect()->route('participant.login');
    }

    public function render()
    {
        return view('livewire.auth.register', [
            'genders' => Gender::all(),
            'religions' => Religion::all(),
            'occupations' => Occupation::all(),
        ])->layout('layouts.guest');
    }
}
