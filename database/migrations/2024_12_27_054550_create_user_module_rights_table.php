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
        Schema::create('user_module_rights', function (Blueprint $table) {
            $table->increments('id'); // Auto-incrementing ID
            $table->unsignedInteger('client_id')->index()->comment('Client ID'); // Client ID
            $table->unsignedInteger('role_id')->index()->comment('Role ID'); // Role ID
            $table->unsignedInteger('menu_id')->index()->comment('Menu ID'); // Menu ID
            $table->enum('can_list', ['Yes', 'No', 'Hidden'])->default('Yes')->comment('List permission'); // List permission
            $table->enum('can_add', ['Yes', 'No', 'Hidden'])->default('Yes')->comment('Add permission'); // Add permission
            $table->enum('can_delete', ['Yes', 'No', 'Hidden'])->default('No')->comment('Delete permission'); // Delete permission
            $table->enum('can_update', ['Yes', 'No', 'Hidden'])->default('Yes')->comment('Update permission'); // Update permission
            $table->enum('can_view', ['Yes', 'No', 'Hidden'])->default('Yes')->comment('View permission'); // View permission
            $table->enum('status', ['Active', 'Inactive'])->default('Active')->comment('Permission status'); // Permission status
            $table->unsignedInteger('created_by')->nullable()->comment('Created by user ID'); // User ID of the creator
            $table->unsignedInteger('updated_by')->nullable()->comment('Updated by user ID'); // User ID of the updater
            $table->softDeletes(); // Soft delete support
            $table->timestamps(); // Created and updated timestamps

            // Define foreign key relationships for client_id, role_id, and menu_id
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('user_roles')->onDelete('cascade');
            $table->foreign('menu_id')->references('id')->on('module_menus')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_module_rights');
    }
};
