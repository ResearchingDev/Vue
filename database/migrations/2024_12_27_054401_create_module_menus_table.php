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
        Schema::create('module_menus', function (Blueprint $table) {
            $table->increments('id'); // Auto-incrementing ID
            $table->unsignedInteger('parent_id')->default(0)->nullable()->index()->comment('Parent menu ID for hierarchical structure'); // Parent menu ID for hierarchical structure
            $table->string('menu_display_name', 255)->nullable()->comment('Name of the module'); // Name of the module
            $table->string('icon', 255)->nullable()->comment('Icon associated with the menu'); // Icon for the menu
            $table->string('url', 250)->nullable()->comment('URL for the menu item'); // URL for the menu item
            $table->enum('module_type', ['Menu', 'SubMenu', 'Custom'])->default('Menu')->comment('Type of the module (Menu, SubMenu, Custom)'); // Type of module (Menu, SubMenu, Custom)
            $table->string('unique_code', 250)->unique()->comment('Unique code for the menu'); // Unique identifier for the menu
            $table->unsignedInteger('sequence_order')->comment('Order in which the menu should appear'); // Menu item display order
            $table->string('description', 1000)->nullable()->comment('Description of the module'); // Description of the module
            $table->enum('status', ['Active', 'Inactive', 'Hidden'])->default('Active')->comment('Status of the menu'); // Menu item status
            $table->softDeletes(); // Soft delete support
            $table->timestamps(); // Created and updated timestamps

            // Define foreign key relationship for parent_id
            $table->foreign('parent_id')->references('id')->on('module_menus')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module_menus');
    }
};
