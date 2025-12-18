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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Landlord-specific fields
            $table->string('ic_number')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();

            // Screening fields
            $table->string('ic_pic')->nullable();
            $table->string('proof_of_address')->nullable();
            $table->string('bankAccount_num')->nullable();
            $table->enum('screening_status', ['pending', 'approved', 'rejected'])
                ->default('pending');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('landlord');
    }

};
