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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedSmallInteger('order')->default(0);
            $table->enum('prioroty', ['high', 'middle', 'low'])->default('low');
            $table->enum('currnecy', ['$', '€'])->default('$');
            $table->unsignedSmallInteger('hourly_rate')->default(100);
            $table->string('color')->default('#ccc');
            $table->dateTime('period_from')->nullable();
            $table->unsignedSmallInteger('active_projects')->nullable();
            $table->unsignedSmallInteger('earnings')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
