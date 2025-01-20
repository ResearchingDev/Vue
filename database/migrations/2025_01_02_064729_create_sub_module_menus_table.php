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
        Schema::create('sub_module_menus', function (Blueprint $table) {
            $table->id(); // `id` will default to unsignedBigInteger
            $table->unsignedBigInteger('parent_id')->default(0)->nullable()->index()->comment('Parent menu ID for hierarchical structure'); // Default value set to 0
            $table->string('module_name', 100);
            $table->string('icon', 50)->nullable();
            $table->string('url', 50)->nullable();
            $table->enum('module_type', ['Menu', 'SubMenu', 'Custom']);
            $table->string('unique_code', 50)->unique();
            $table->unsignedInteger('sequence_order');
            $table->text('description')->nullable();
            $table->enum('status', ['Active', 'Inactive', 'Hidden']);
            $table->softDeletes(); // Soft delete support
            $table->timestamps();

            // Define foreign key relationship for parent_id
            $table->foreign('parent_id')->references('id')->on('sub_module_menus')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_module_menus');
    }
};
