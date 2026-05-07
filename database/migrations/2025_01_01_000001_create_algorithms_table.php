<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Matches Firebase 'libraries' and 'articles' collections exactly.
     */
    public function up(): void
    {
        Schema::create('algorithms', function (Blueprint $table) {
            $table->id();

            // Firebase document ID (used for linking details page)
            $table->string('firebase_id')->unique();

            // Basic library info
            $table->string('name');
            $table->string('developer')->nullable();
            $table->text('language')->nullable();         // e.g. "C, C++, Java"
            $table->string('latest_version')->nullable(); // Firebase: latest-version
            $table->string('latest_release')->nullable(); // Firebase: latest-release
            $table->string('license')->nullable();
            $table->boolean('open_source')->default(false); // Firebase: open-source
            $table->string('github')->nullable();
            $table->boolean('show')->default(true);       // Firebase: show

            // PQC
            $table->json('pqc_algorithm')->nullable();    // Firebase: pqc-algorithm (array)

            // Content tabs
            $table->text('overview')->nullable();
            $table->text('limitation')->nullable();

            // Installation steps (array of {command, explanation, imageURL, imageExplanation})
            $table->json('installation_step')->nullable(); // Firebase: installation-step

            // Testing (array of {command, explanation})
            $table->json('testing')->nullable();

            // Testing/result images (array of {imageURL, explanation})
            $table->json('image')->nullable();

            // Sync tracking
            $table->timestamp('firebase_updated_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('algorithms');
    }
};
