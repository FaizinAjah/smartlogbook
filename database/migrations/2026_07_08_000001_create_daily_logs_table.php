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
        Schema::disableForeignKeyConstraints();

        if (! Schema::hasTable('daily_logs')) {
            Schema::create('daily_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->date('log_date')->nullable()->index();
                $table->string('title', 255);
                $table->text('description')->nullable();
                $table->string('attendance_status', 50)->default('masuk');
                $table->string('status', 50)->default('menunggu_review')->index();
                $table->foreignId('created_by')->nullable();
                $table->foreignId('updated_by')->nullable();
                $table->foreignId('deleted_by')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->index('user_id');
            });
        }

        if (! Schema::hasTable('daily_log_images')) {
            Schema::create('daily_log_images', function (Blueprint $table) {
                $table->id();
                $table->foreignId('daily_log_id')->constrained('daily_logs')->cascadeOnDelete();
                $table->string('path', 255);
                $table->string('original_name', 255);
                $table->string('mime', 100);
                $table->unsignedInteger('size');
                $table->smallInteger('sort_order')->default(0);
                $table->foreignId('created_by')->nullable();
                $table->timestamps();

                $table->index('daily_log_id');
            });
        }

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('daily_log_images');
        Schema::dropIfExists('daily_logs');
        Schema::enableForeignKeyConstraints();
    }
};
