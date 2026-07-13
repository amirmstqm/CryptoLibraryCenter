<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('algorithms', function (Blueprint $table) {
            if (! Schema::hasColumn('algorithms', 'category')) {
                $table->string('category')->nullable()->after('show');
            }
        });
    }

    public function down(): void
    {
        Schema::table('algorithms', function (Blueprint $table) {
            if (Schema::hasColumn('algorithms', 'category')) {
                $table->dropColumn('category');
            }
        });
    }
};
