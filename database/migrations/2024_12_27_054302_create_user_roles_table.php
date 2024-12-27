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
        Schema::create('user_roles', function (Blueprint $table) {
            $table->increments('id'); // Auto-incrementing ID
            $table->unsignedInteger('client_id')->index()->comment('Client ID'); // Client ID
            $table->string('role_name', 50)->comment('Role name'); // Role name
            $table->string('role_unique_code', 50)->nullable()->unique()->comment('Unique code for the role'); // Unique code for the role
            $table->enum('web_access', ['Yes', 'No'])->default('Yes')->comment('Web access permission'); // Web access permission
            $table->enum('mobile_access', ['Yes', 'No'])->default('Yes')->comment('Mobile access permission'); // Mobile access permission
            $table->enum('primary_access', ['Yes', 'No'])->default('No')->comment('Primary access for the role'); // Whether the role has primary access
            $table->enum('status', ['Active', 'Inactive'])->default('Active')->comment('Role status'); // Role status
            $table->unsignedInteger('created_by')->nullable()->comment('Created by user ID'); // User ID of the creator
            $table->unsignedInteger('updated_by')->nullable()->comment('Updated by user ID'); // User ID of the updater
            $table->softDeletes(); // Soft delete support
            $table->timestamps(); // Created and updated timestamps

            // Define foreign key relationship for client_id
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_roles');
    }
};
