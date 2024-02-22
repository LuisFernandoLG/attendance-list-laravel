<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    public const default_image_url = 'https://dummyimage.com/640x360/fff/aaa';
    public const eventType = [
        'CONTROLLED' => 'CONTROLLED',
        'UNCONTROLLED' => 'UNCONTROLLED',
    ];
    public const eventTypeList = [
        self::eventType['CONTROLLED'],
        self::eventType['UNCONTROLLED'],
    ];

    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('image_url')->nullable();
            $table->string('type')->list(self::eventTypeList);
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
