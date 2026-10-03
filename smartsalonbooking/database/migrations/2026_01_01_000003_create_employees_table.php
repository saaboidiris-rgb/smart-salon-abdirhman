<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable()->unique();
            $table->string('phone', 30)->nullable();
            $table->string('specialization')->nullable();
            $table->string('photo')->nullable();
            $table->text('bio')->nullable();
            // Which days of the week the employee works, e.g. ["mon","tue","wed"]
            $table->json('working_days')->nullable();
            $table->time('working_hours_start')->default('09:00:00');
            $table->time('working_hours_end')->default('18:00:00');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
