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
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id'); // Auto-incrementing ID
            $table->string('client_name', 255)->nullable()->comment('Client name'); // Client name
            $table->string('email', 100)->nullable()->comment('Client email'); // Client email
            $table->string('phone_number', 50)->nullable()->comment('Client phone number'); // Client phone number
            $table->string('logo', 100)->nullable()->comment('Client logo'); // Client logo
            $table->text('address')->nullable()->comment('Client address'); // Client address
            $table->string('city', 100)->nullable()->comment('Client city'); // Client city
            $table->string('state', 30)->nullable()->comment('Client state'); // Client state
            $table->string('zipcode', 50)->nullable()->comment('Client zipcode'); // Client zipcode
            $table->string('timezone', 255)->nullable()->comment('Client timezone'); // Client timezone
            $table->enum('status', ['Active', 'Inactive'])->default('Active')->comment('Client status'); // Client status
            $table->unsignedInteger('created_by')->nullable()->comment('Created by user ID'); // User ID of the creator
            $table->unsignedInteger('updated_by')->nullable()->comment('Updated by user ID'); // User ID of the updater
            $table->softDeletes(); // Soft delete support
            $table->timestamps(); // Created and updated timestamps

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
