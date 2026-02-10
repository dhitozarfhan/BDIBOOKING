<?php

namespace Tests\Feature;

use App\Models\Participant;
use App\Models\Gender;
use App\Models\Religion;
use App\Models\Occupation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;
use App\Livewire\Auth\KtpLogin;
use App\Services\OcrService;

class KtpLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_ktp_login_page_is_accessible()
    {
        $response = $this->get(route('participant.ktp.login'));
        $response->assertStatus(200);
        $response->assertSeeLivewire(KtpLogin::class);
    }

    public function test_scan_existing_user_logs_in()
    {
        // Setup initial data
        $gender = Gender::create(['code' => 'M', 'type' => 'Male']);
        $religion = Religion::create(['name' => 'Islam']);
        $occupation = Occupation::create(['name' => 'Developer']);
        
        // Create existing participant with specific NIK
        $participant = Participant::create([
            'nik' => '1234567890123456', // Must match MockOcrService NIK
            'name' => 'Existing User',
            'email' => 'existing@example.com',
            'password' => bcrypt('password'),
            'birth_place' => 'Jakarta',
            'birth_date' => '1990-01-01',
            'gender_id' => $gender->id,
            'religion_id' => $religion->id,
            'occupation_id' => $occupation->id,
            'phone' => '08123456789',
            'address' => 'Test Address',
        ]);

        Storage::fake('avatars');
        $file = UploadedFile::fake()->image('ktp.jpg');

        Livewire::test(KtpLogin::class)
            ->set('ktp_image', $file)
            ->call('scan')
            ->assertRedirect(route('participant.dashboard'));

        $this->assertAuthenticatedAs($participant, 'participant');
    }

    public function test_scan_new_user_redirects_to_register_with_data()
    {
        // Ensure no participant exists with the mock NIK
        // The MockOcrService returns '1234567890123456' by default. 
        // We need to modify the mock or just ensure that NIK is NOT in DB.
        
        Storage::fake('avatars');
        $file = UploadedFile::fake()->image('ktp.jpg');

        Livewire::test(KtpLogin::class)
            ->set('ktp_image', $file)
            ->call('scan')
            ->assertRedirect(route('participant.register'))
            ->assertSessionHas('ocr_data');
            
        $this->assertGuest('participant');
        
        // Follow redirect to register
        $response = $this->get(route('participant.register'));
        $response->assertStatus(200);
        
        // Verify pre-filled data is seen in the Livewire component 
        // Note: Livewire tests on the redirect target might need separate test call or check view data
        
        // Check if session data is correct
        $ocrData = session('ocr_data');
        $this->assertEquals('1234567890123456', $ocrData['nik']);
    }
}
