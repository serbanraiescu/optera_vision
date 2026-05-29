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
        Schema::table('quote_requests', function (Blueprint $table) {
            $table->foreignId('client_id')->nullable()->after('assigned_to')->constrained('clients')->onDelete('set null');
            $table->boolean('is_important')->default(false)->after('client_id');
            $table->timestamp('assigned_at')->nullable()->after('is_important');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quote_requests', function (Blueprint $table) {
            // Drop foreign key with standard Laravel naming convention
            $table->dropForeign(['client_id']);
            $table->dropColumn(['client_id', 'is_important', 'assigned_at']);
        });
    }
};
