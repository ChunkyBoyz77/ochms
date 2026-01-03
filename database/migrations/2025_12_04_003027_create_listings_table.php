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
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            // Owner
            $table->foreignId('landlord_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // Occupant (assigned after booking approval)
            $table->foreignId('ocs_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->foreignId('last_occupied_ocs_id')
                  ->nullable()
                  ->constrained('ocs')
                  ->nullOnDelete();

            // Basic info
            $table->string('title');
            $table->enum('property_type', ['Room', 'Apartment', 'House']);
            $table->unsignedTinyInteger('bedrooms')->default(0);
            $table->unsignedTinyInteger('bathrooms')->default(0);
            $table->unsignedTinyInteger('beds')->default(0);
            $table->unsignedTinyInteger('max_occupants')->default(1);
            $table->text('description');

            // Location
            $table->string('address');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->float('distance_to_umpsa')->nullable();

            // Pricing
            $table->decimal('monthly_rent', 8, 2);
            $table->decimal('deposit', 8, 2)->nullable();

            // Amenities
            $table->json('amenities')->nullable();

            // Policies
            $table->text('policy_cancellation')->nullable();
            $table->text('policy_refund')->nullable();
            $table->text('policy_early_movein')->nullable();
            $table->text('policy_late_payment')->nullable();
            $table->text('policy_additional')->nullable();

            // Media
            $table->string('grant_document_path')->nullable();

            // Status
            $table->enum('status', [
                'pending',
                'published',
                'requested',
                'occupied',
                'vacated',
                'rejected'
            ])->default('pending');

            $table->timestamp('published_at')->nullable();
                    
            $table->timestamps();
        });
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
