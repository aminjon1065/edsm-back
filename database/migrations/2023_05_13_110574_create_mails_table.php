<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('to')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('from')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('document_id')->nullable()->references('id')->on('mails')->onDelete('cascade');
            $table->dateTime('send_date');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mails');
    }
};