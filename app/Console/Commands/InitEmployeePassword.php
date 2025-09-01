<?php

namespace App\Console\Commands;

use App\Enums\EmployeeStatus;
use App\Models\Employee;
use App\Services\Wablas;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class InitEmployeePassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:employee-password';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Init admin password for first time';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $wa = new Wablas();

        $cities = [
            "yogyakarta",
            "pekalongan",
            "purwakarta",
            "samarinda",
            "pontianak",
            "payakumbuh",
            "prabumulih",
            "pagaralam",
            "ketapang",
            "sukabumi",
            "indramayu",
            "majalengka",
            "sumedang",
            "banjarbaru",
            "martapura",
            "balikpapan",
            "meulaboh",
            "takengon",
            "sinabang",
            "waikabubak",
            "saumlaki",
            "mamberamo",
            "pandeglang",
            "wonosobo",
            "boyolali",
            "purworejo",
            "salatiga",
            "semarang",
            "surabaya",
            "pasuruan",
            "bojonegoro",
            "lamongan",
            "mojokerto",
            "bangkalan",
            "pamekasan",
            "banyuwangi",
            "situbondo",
            "bondowoso",
            "makassar",
            "parepare",
            "morowali",
            "donggala",
            "pariaman",
            "bengkulu",
            "mukomuko",
            "temanggung",
            "tangerang",
            "pemalang",
            "sukamara",
            "kapuasbar",
            "tapinbaru",
            "tanahlaut",
            "hulusungai",
            "paluindah",
            "halmahera",
            "namleaind",
            "majeneind",
            "butonbaru",
            "kolakaind",
            "kendalind",
            "batubara",
            "mentawai",
            "belitung",
            "jayapura"
        ];

        //get all employee with status as cpns, pns, pppk or nonpns then reset password. the plain password send to them with wablas library
        $employees = Employee::whereIn('employee_status_id', [EmployeeStatus::CPNS->value, EmployeeStatus::PNS->value, EmployeeStatus::PPPK->value, EmployeeStatus::NonPNS->value])->get();
        foreach ($employees as $employee) {
            if(!empty($employee->mobile) && $employee->is_active){
                //ambil password secara acak dari array cities
                $plainPassword = $cities[array_rand($cities)];
                $employee->update(['password' => Hash::make($plainPassword)]);
                $wa->sendText($employee->mobile, "Yth. ".($employee->gender_id == 1 ? "Bapak" : "Ibu")." {$employee->name},
Mulai sekarang, Website BDI Yogyakarta tampil dengan wajah baru!
Mari ikut berkontribusi dengan mengisi konten melalui berita atau galeri kegiatan Anda.

Untuk mendukung kemudahan akses, akun Anda telah kami reset.
Silakan login ke: https://bdiyogyakarta.kemenperin.go.id/admin

Username: {$employee->username}

Password baru: {$plainPassword}

Yuk segera login dan mulai berbagi konten kegiatan Anda! 🚀");
            }
        }
    }
}
