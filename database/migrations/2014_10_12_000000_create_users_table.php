<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public const roles = ['student', 'teacher', 'none'];
    public const authTypes = ['email', 'google'];
    public const defaultImageUrl = 'https://www.gravatar.com/avatar';
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('rol')->list(self::roles)->default('none');
            $table->string('auth_type')->list(self::authTypes)->default('email');
            $table->text('image_url')->nullable();
            $table->string('timezone');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};