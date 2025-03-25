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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('car_id')->constrained('cars', 'id')->onDelete('cascade');
            $table->integer('lognumber');
            // A lognumber értéke ügyfelenként és autónként növekszik.
            $table->integer('event_id');
            $table->dateTime('eventtime')->nullable();
            $table->integer('document_id')->nullable();
            $table->timestamps();

            $table->index(['client_id', 'car_id', 'lognumber']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_logs');
    }
};
