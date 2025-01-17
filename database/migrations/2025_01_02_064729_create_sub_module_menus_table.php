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

            $table->id(); // Auto-incrementing ID
            $table->string('title'); // Menu title
            $table->string('path')->nullable(); // Path for link-type items
            $table->enum('type', ['link', 'sub', 'headtitle', 'custom']); // Type of menu item
            $table->unsignedBigInteger('parent_id')->nullable(); // Parent ID for submenus (NULL for top-level)
            $table->string('icon')->nullable(); // Icon for the menu item
            $table->string('iconf')->nullable(); // Filled icon for the menu item
            $table->string('badge_type', 50)->nullable(); // Badge type (e.g., light-primary)
            $table->boolean('active')->default(true); // Whether the menu item is active
            $table->enum('role', ['super_admin', 'client', 'custom']); // Role-based visibility
            $table->integer('sort_order')->default(0); // Used for ordering items
            $table->string('unique_code')->unique(); // Unique identifier for the menu item
            $table->text('description')->nullable(); // Optional description of the menu item
            $table->enum('status', ['Active', 'Inactive', 'Hidden']); // Status of the menu item
            $table->timestamp('deleted_at')->nullable(); // Soft delete timestamp
            $table->timestamps(); // Created at and Updated at timestamps
            // Foreign key constraint for parent_id
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
