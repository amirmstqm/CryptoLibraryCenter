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
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('password');
                $table->string('profile_picture')->nullable();
                $table->text('bio')->nullable();
                $table->string('expertise_level')->default('beginner'); // beginner, intermediate, advanced
                $table->json('interests')->nullable(); // JSON array of interests
                $table->timestamp('email_verified_at')->nullable();
                $table->rememberToken();
                $table->timestamps();
            });
        } else {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'profile_picture')) {
                    $table->string('profile_picture')->nullable();
                }
                if (!Schema::hasColumn('users', 'bio')) {
                    $table->text('bio')->nullable();
                }
                if (!Schema::hasColumn('users', 'expertise_level')) {
                    $table->string('expertise_level')->default('beginner');
                }
                if (!Schema::hasColumn('users', 'interests')) {
                    $table->json('interests')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
