<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Invoice;
use App\Models\Participant;
use App\Models\Training;
use App\Models\Occupation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class ParticipantFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_participant_can_register_login_book_and_upload_payment_proof()
    {
        Storage::fake('public');

        // 1. Setup Data
        DB::table('genders')->insert([
            ['id' => 1, 'code' => 'L', 'type' => 'Laki-laki', 'image' => 'male.png'],
            ['id' => 2, 'code' => 'P', 'type' => 'Perempuan', 'image' => 'female.png'],
        ]);
        
        DB::table('religions')->insert([
            ['id' => 1, 'name' => 'Islam'],
        ]);

        $occupation = Occupation::create(['name' => 'Software Engineer']);
        
        $training = Training::create([
            'title' => 'Laravel Advanced',
            'description' => 'Deep dive into Laravel',
            'start_date' => now()->addDays(5),
            'end_date' => now()->addDays(7),
            'location' => 'Online',
            'price' => 500000,
            'quota' => 20,
            'is_published' => true,
        ]);

        // 2. Register
        Livewire::test(\App\Livewire\Auth\Register::class)
            ->set('nik', '1234567890123456')
            ->set('name', 'John Doe')
            ->set('email', 'john@example.com')
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->set('birth_place', 'Jakarta')
            ->set('birth_date', '1990-01-01')
            ->set('gender_id', 1)
            ->set('religion_id', 1)
            ->set('phone', '08123456789')
            ->set('address', 'Jl. Test No. 1')
            ->set('occupation_id', $occupation->id)
            ->call('register')
            ->assertRedirect(route('participant.dashboard'));

        $this->assertDatabaseHas('participants', ['email' => 'john@example.com']);

        // 3. Login Check
        $this->assertAuthenticated('participant');

        // 4. Booking
        Livewire::test(\App\Livewire\Training\Detail::class, ['id_diklat' => $training->id])
            ->call('register')
            ->assertRedirect(route('participant.dashboard'));

        $booking = Booking::first();
        $this->assertNotNull($booking);
        $this->assertEquals($training->id, $booking->bookable_id);
        $this->assertEquals('pending', $booking->status);

        // 5. Admin Creates Invoice (Simulated)
        $invoice = Invoice::create([
            'booking_id' => $booking->id,
            'billing_code' => 'BILL-001',
            'amount' => $training->price,
            'status' => 'unpaid',
            'issued_at' => now(),
            'due_date' => now()->addDays(3),
        ]);

        // 6. Upload Proof via Dashboard
        $file = UploadedFile::fake()->image('payment.jpg');

        Livewire::test(\App\Livewire\Participant\Dashboard::class)
            ->set("payment_proofs.{$invoice->id}", $file)
            ->call('uploadProof', $invoice->id);

        $invoice->refresh();
        $this->assertNotNull($invoice->payment_proof);
        $this->assertEquals('unpaid', $invoice->status); // Status remains unpaid until admin verification

        // 7. Verify file exists
        Storage::disk('public')->assertExists($invoice->payment_proof);
    }
}
