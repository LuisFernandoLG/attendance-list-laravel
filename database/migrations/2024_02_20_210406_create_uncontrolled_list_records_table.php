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
        Schema::create('uncontrolled_list_records', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('custom_id')->unique();
            $table->string('email');
            $table->string('phone')->nullable();
            $table->text('image_url')->nullable();
            $table->boolean('notifyByEmail')->default(true);
            $table->boolean('notifyByPhone')->default(false);
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uncontrolled_list_records');
    }
};
