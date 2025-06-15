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
            $table->foreignId('merchant_id')->constrained(); // note belongs to
            $table->foreignId('created_by')->constrained('users'); // user who wrote it
            $table->foreignId('assigned_to')->nullable()->constrained('users'); // optional assignee

            $table->id();
            $table->string('uid')->unique();
            $table->string('title'); // note title
            $table->text('body'); // note contents
            $table->string('type')->default('info'); // info, task, alert, etc.
            $table->string('status')->default('open'); // open, closed, in progress etc.
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
