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
        Schema::create('sub_user_rights', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID (uses unsignedBigInteger by default)
            $table->unsignedBigInteger('user_id')->index()->comment('Client ID'); // Client ID
            $table->unsignedBigInteger('role_id')->index()->comment('Role ID'); // Role ID
            $table->unsignedBigInteger('menu_id')->index()->comment('Menu ID'); // Menu ID (changed to unsignedBigInteger)
            $table->enum('can_list', ['Yes', 'No', 'Hidden'])->default('Yes')->comment('List permission');
            $table->enum('can_add', ['Yes', 'No', 'Hidden'])->default('Yes')->comment('Add permission');
            $table->enum('can_delete', ['Yes', 'No', 'Hidden'])->default('No')->comment('Delete permission');
            $table->enum('can_update', ['Yes', 'No', 'Hidden'])->default('Yes')->comment('Update permission');
            $table->enum('can_view', ['Yes', 'No', 'Hidden'])->default('Yes')->comment('View permission');
            $table->enum('status', ['Active', 'Inactive'])->default('Active')->comment('Permission status');
            $table->unsignedBigInteger('created_by')->nullable()->comment('Created by user ID');
            $table->unsignedBigInteger('updated_by')->nullable()->comment('Updated by user ID');
            $table->softDeletes();
            $table->timestamps();

            // Define foreign key relationships
            // $table->foreign('user_id')->references('id')->on('sub_users')->onDelete('cascade');
            // $table->foreign('role_id')->references('id')->on('sub_user_roles')->onDelete('cascade');
            // $table->foreign('menu_id')->references('id')->on('sub_module_menus')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_user_rights');
    }
};
