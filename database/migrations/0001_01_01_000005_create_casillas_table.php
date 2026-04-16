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
        Schema::create('casillas', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ["Rural", "Urbana", "Especial"])->nullable();
            $table->integer('district')->nullable();
            $table->string('perimeter')->nullable();
            $table->string('place')->nullable();
            $table->string('location')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->dateTime("deleted_at")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('casillas');
    }
};