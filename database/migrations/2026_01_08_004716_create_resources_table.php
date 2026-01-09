<?php

// database/migrations/xxxx_xx_xx_create_resources_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->id();

            $table->foreignId('admin_id')
                    ->constrained('jhepa_admin')
                    ->cascadeOnDelete();

            $table->string('title');
            $table->string('category')->nullable(); // Welfare, Emergency, Academic, etc
            $table->string('external_link')->nullable(); // optional URL
            $table->string('image_path')->nullable(); // optional banner/image
            $table->longText('description'); // Quill HTML



            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
