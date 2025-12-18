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
        Schema::create('ocs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Add OCS-specific fields
            $table->string('matric_id')->nullable();
            $table->string('faculty')->nullable();
            $table->string('course')->nullable();
            $table->integer('study_year')->nullable();
            $table->integer('current_semester')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ocs');
    }

};
