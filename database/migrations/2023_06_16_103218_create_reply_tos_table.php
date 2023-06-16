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
        Schema::create('reply_tos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('reply_to')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('mail_id')->references('id')->on('mails')->onDelete('cascade');
            $table->foreignId('document_id')->references('id')->on('documents')->onDelete('cascade');
            $table->foreignId('mail_reply_id')->references('id')->on('mails')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reply_tos');
    }
};
