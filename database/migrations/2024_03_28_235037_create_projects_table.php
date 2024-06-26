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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->string('name');
            $table->text('notes');
            $table->unsignedSmallInteger('order')->default(0);
            $table->enum('prioroty', ['high', 'middle', 'low'])->default('low');
            $table->unsignedSmallInteger('hourly_rate')->default(100);
            $table->unsignedSmallInteger('hourly_rate_two')->nullable();
            $table->dateTime('period_from')->nullable();
            $table->dateTime('ended_at')->nullable();
            $table->unsignedMediumInteger('total_time')->nullable();
            $table->boolean('selected')->default(false);
            $table->unsignedSmallInteger('extra_time')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
