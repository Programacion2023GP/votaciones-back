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
        Schema::create('ballots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users'); //casilla
            $table->foreignId('vote_1')->nullable()->constrained('projects');
            $table->foreignId('vote_2')->nullable()->constrained('projects');
            $table->foreignId('vote_3')->nullable()->constrained('projects');
            $table->foreignId('vote_4')->nullable()->constrained('projects');
            $table->foreignId('vote_5')->nullable()->constrained('projects');

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
        Schema::dropIfExists('ballots');
    }
};
