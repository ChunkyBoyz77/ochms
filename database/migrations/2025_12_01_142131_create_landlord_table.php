<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('landlord', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            // Admin who reviewed the screening
            $table->foreignId('reviewed_by_admin_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // === DOCUMENTS (stored in DB as binary) ===
            $table->string('ic_pic')->nullable();               // PDF / JPG
            $table->string('proof_of_address')->nullable();     // PDF / JPG

            // === FINANCIAL ===
            $table->string('bank_account_num')->nullable();
            $table->string('bank_name')->nullable();

            // === SCREENING QUESTIONS ===
            $table->boolean('has_criminal_record')->nullable();
            $table->text('criminal_record_details')->nullable();

            // === LEGAL / CONSENT ===
            $table->boolean('agreement_accepted')->default(false);

            // === STATUS TRACKING ===
            $table->enum('screening_status', ['pending', 'approved', 'rejected'])
                ->default('pending');
            
            $table->text('screening_notes')->nullable();


            $table->timestamp('screening_submitted_at')->nullable();
            $table->timestamp('screening_reviewed_at')->nullable();

            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::dropIfExists('landlord');
    }

};
