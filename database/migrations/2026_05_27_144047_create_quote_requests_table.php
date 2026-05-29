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
        Schema::create('quote_requests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('email');
            $table->string('locality')->nullable();
            $table->string('location_type'); // residential, commercial, industrial etc.
            $table->boolean('is_upgrade')->default(false); // new system or upgrade existing system
            $table->integer('camera_count')->default(0);
            $table->text('message')->nullable();
            $table->string('lead_source')->nullable(); // website form, telephone, direct etc.
            $table->enum('status', [
                'nou', 
                'contactat', 
                'programare_evaluare', 
                'ofertat', 
                'acceptat', 
                'in_lucru', 
                'finalizat', 
                'pierdut'
            ])->default('nou');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quote_requests');
    }
};
