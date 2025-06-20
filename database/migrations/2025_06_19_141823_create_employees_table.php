<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('username', 18)->unique();
            $table->string('nip', 18)->nullable()->unique();
            $table->string('nip_intranet', 50)->nullable();
            $table->string('name', 80)->nullable();
            $table->string('title_pre', 30)->nullable();
            $table->string('title_post', 100)->nullable();
            $table->string('birth_place', 50)->nullable();
            $table->date('birth_date')->nullable();
            $table->integer('gender_id')->nullable();
            $table->integer('religion_id')->nullable();
            $table->integer('education_id')->nullable();
            $table->integer('employee_status_id')->nullable();
            $table->integer('rank_id')->nullable();
            $table->date('tmt_rank')->nullable();
            $table->integer('position_id')->nullable();
            $table->date('tmt_position')->nullable();
            $table->date('tmt_work')->nullable();
            $table->date('tmt_pns')->nullable();
            $table->string('karpeg_number', 100)->nullable();
            $table->string('ktp_number', 100)->nullable();
            $table->string('askes_number', 100)->nullable();
            $table->string('npwp', 100)->nullable();
            $table->text('address')->nullable();
            $table->string('phone', 100)->nullable();
            $table->string('mobile', 100)->nullable();
            $table->string('email', 100);
            $table->string('image', 100)->nullable();
            $table->string('thumbnail', 100)->nullable();
            $table->string('password');
            $table->boolean('can_edited')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_sync_at')->nullable();
            $table->timestamp('last_renew_password_at')->nullable();
            $table->boolean('force_renew_password')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
