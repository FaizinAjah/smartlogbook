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

        if (! Schema::hasTable('sidebar_menu_groups')) {
            Schema::create('sidebar_menu_groups', function (Blueprint $table) {
                $table->id();
                $table->string('key', 50)->unique();
                $table->string('label', 100);
                $table->string('color', 50)->default('blue');
                $table->smallInteger('sort_order')->default(0);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('sidebar_menus')) {
            Schema::create('sidebar_menus', function (Blueprint $table) {
                $table->id();
                $table->foreignId('parent_id')->nullable()->index();
                $table->string('label', 255);
                $table->string('route_name', 255)->nullable();
                $table->string('icon', 255)->nullable();
                $table->string('group', 50)->default('utama');
                $table->smallInteger('sort_order')->default(0);
                $table->tinyInteger('is_active')->default(1);
                $table->foreignId('created_by')->nullable();
                $table->foreignId('updated_by')->nullable();
                $table->foreignId('deleted_by')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->index(['group', 'sort_order']);
            });
        }

        if (! Schema::hasTable('sidebar_menu_accesses')) {
            Schema::create('sidebar_menu_accesses', function (Blueprint $table) {
                $table->id();
                $table->foreignId('sidebar_menu_id')->index();
                $table->tinyInteger('access_type');
                $table->foreignId('created_by')->nullable();
                $table->timestamp('created_at')->nullable();

                $table->unique(['sidebar_menu_id', 'access_type']);
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
        Schema::dropIfExists('sidebar_menu_accesses');
        Schema::dropIfExists('sidebar_menus');
        Schema::dropIfExists('sidebar_menu_groups');
        Schema::enableForeignKeyConstraints();
    }
};
