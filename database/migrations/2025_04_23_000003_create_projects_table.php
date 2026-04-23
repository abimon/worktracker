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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('developer_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('budget', 12, 2);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->enum('status', ['draft', 'active', 'on_hold', 'completed', 'archived'])->default('draft');
            $table->enum('progress', ['planning', 'in_progress', 'review', 'completed'])->default('planning');
            $table->integer('progress_percentage')->default(0);
            $table->date('deadline')->nullable();
            $table->date('start_date')->nullable();
            $table->string('preview_url')->nullable(); // Website or GitHub link
            $table->string('share_token')->unique()->nullable(); // For client sharing
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
