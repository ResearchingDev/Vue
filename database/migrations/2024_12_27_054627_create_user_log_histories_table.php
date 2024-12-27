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
        Schema::create('user_log_histories', function (Blueprint $table) {
            $table->increments('id'); // Auto-incrementing ID
            $table->unsignedInteger('user_id')->nullable()->index()->comment('User ID'); // User ID
            $table->string('ip_address', 50)->nullable()->comment('IP address of the user'); // IP address
            $table->enum('user_type', ['Super Admin', 'Client', 'User'])->comment('User type (Super Admin, Client, User)'); // Type of user
            $table->dateTime('login_at')->nullable()->comment('Login timestamp'); // Login timestamp
            $table->enum('login_source', ['Web', 'Tablet', 'Mobile'])->default('Web')->comment('Source of login'); // Source of login
            $table->enum('logout_source', ['Web', 'Tablet', 'Mobile'])->default('Web')->comment('Source of logout'); // Source of logout
            $table->string('browser_details', 255)->nullable()->comment('Browser/device details for identifying last login'); // Browser or device details
            $table->dateTime('logout_at')->nullable()->comment('Logout timestamp'); // Logout timestamp
            $table->enum('status', ['Active', 'Inactive'])->default('Active')->comment('Status of the log'); // Status of the log
            $table->unsignedInteger('created_by')->nullable()->comment('Created by user ID'); // User ID of the creator
            $table->unsignedInteger('updated_by')->nullable()->comment('Updated by user ID'); // User ID of the updater
            $table->softDeletes(); // Soft delete support
            $table->timestamps(); // Created and updated timestamps

            // Define foreign key relationship for user_id
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_log_histories');
    }
};
