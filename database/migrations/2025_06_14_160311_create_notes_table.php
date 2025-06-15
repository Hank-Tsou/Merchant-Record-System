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
        Schema::create('notes', function (Blueprint $table) {
            $table->foreignId('merchant_id')->constrained();
            $table->foreignId('created_by')->constrained('users'); // user who wrote it
            $table->foreignId('assigned_to')->nullable()->constrained('users'); // optional assignee

            $table->id();
            $table->string('uid')->unique();
            $table->string('title');
            $table->text('body');

            $table->string('type')->default('info'); // info, task, alert, etc.
            $table->string('status')->default('open'); // open, closed, etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
