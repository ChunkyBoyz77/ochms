<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            /* ================= RELATIONS ================= */

            // Listing being reviewed
            $table->foreignId('listing_id')
                  ->constrained('listings')
                  ->cascadeOnDelete();

            // OCS who wrote the review
            $table->foreignId('ocs_id')
                  ->constrained('ocs')
                  ->cascadeOnDelete();

            /* ================= REVIEW CONTENT ================= */

            $table->unsignedTinyInteger('rating'); // 1–5
            $table->text('review')->nullable();
            $table->date('stay_from');
            $table->date('stay_until');

            /* ================= META ================= */

            $table->timestamps();

            /* ================= CONSTRAINT ================= */

            // One review per student per listing
            $table->unique(['listing_id', 'ocs_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
