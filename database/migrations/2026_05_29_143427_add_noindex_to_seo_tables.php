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
        Schema::table('pages', function (Blueprint $table) {
            $table->boolean('noindex')->default(false)->after('meta_description');
        });
        Schema::table('services', function (Blueprint $table) {
            $table->boolean('noindex')->default(false)->after('meta_description');
        });
        Schema::table('projects', function (Blueprint $table) {
            $table->boolean('noindex')->default(false)->after('meta_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn('noindex');
        });
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('noindex');
        });
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('noindex');
        });
    }
};
