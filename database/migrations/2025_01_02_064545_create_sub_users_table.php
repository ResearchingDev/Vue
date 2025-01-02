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
        Schema::create('sub_users', function (Blueprint $table) {
            $table->increments('id')->comment('Auto-incrementing ID for users'); // Auto-incrementing ID
            $table->unsignedInteger('client_id')->nullable()->index()->comment('Client ID linked to the clients table'); // Client ID (linked to clients table)
            $table->unsignedInteger('role_id')->nullable()->index()->comment('Role ID linked to user_roles table'); // Role ID (linked to user_roles table)
            $table->string('username', length: 100)->nullable()->unique()->comment('Unique username for the user'); // Username
            $table->string('email', 50)->nullable()->unique()->comment('Unique email for the user'); // Email
            $table->string('password', 255)->comment('User password (hashed)'); // User password
            $table->string('secondary_password', 255)->comment('User Secondary password (hashed)'); // User password
            $table->string('first_name', 50)->nullable()->comment('First name of the user'); // First name
            $table->string('last_name', length: 50)->nullable()->comment('Last name of the user'); // Last name
            $table->string('phone_number', 20)->nullable()->comment('Phone number of the user'); // Mobile number
            $table->string('alter_phone_number', 20)->nullable()->comment('Alter phone number of the user'); // Mobile number
            $table->enum('status', ['Active', 'Inactive'])->default('Active')->comment('User status (Active/Inactive)'); // User status
            $table->enum('user_type', ['Super Admin', 'Client', 'User'])->default('User')->comment('User type (Super Admin/Client/User)'); // Type of user
            $table->enum('can_login', ['Yes', 'No'])->default('Yes')->comment('Whether the user can log in (Yes/No)'); // Whether the user can login
            $table->rememberToken()->comment('Token for remembering user sessions'); // For remembering user sessions
            $table->unsignedInteger('created_by')->nullable()->comment('Created by user ID'); // User ID of the creator
            $table->unsignedInteger('updated_by')->nullable()->comment('Updated by user ID'); // User ID of the updater
            $table->softDeletes(); // Soft delete support
            $table->timestamps(); // Created and updated timestamps

            // Define foreign key relationships for client_id and role_id
            $table->foreign('client_id')->references('id')->on('sub_clients')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('sub_user_roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_users');
    }
};