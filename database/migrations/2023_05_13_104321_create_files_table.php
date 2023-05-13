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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('name_file');
            $table->string('extension_file');
            $table->unsignedBigInteger('document_id');
            $table->foreign('document_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->unsignedBigInteger('created_user_id');
            $table->unsignedBigInteger('updated_user_id')->nullable();
            $table->foreign('created_user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('updated_user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->dateTime('created_date');
            $table->dateTime('updated_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
