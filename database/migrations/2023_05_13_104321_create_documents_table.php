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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('title_document');
            $table->text('description_document')->nullable();
            $table->text('content')->nullable();
            $table->string('region');
            $table->boolean('importance');
            $table->string('status');
            $table->foreignId('created_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('updated_user_id')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->dateTime('created_date');
            $table->dateTime('updated_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
