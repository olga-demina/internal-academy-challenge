<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('workshop_id')
                ->constrained('workshops')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->enum('status', ['confirmed', 'waiting']);
            $table->dateTime('promoted_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
