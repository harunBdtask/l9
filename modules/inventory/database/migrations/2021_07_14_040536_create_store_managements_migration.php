<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreManagementsMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_floors', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('factory_id');
            $table->unsignedInteger('store_id');
            $table->string('name', 30);
            $table->unsignedInteger('sequence')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });


        Schema::create('store_rooms', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('factory_id');
            $table->unsignedInteger('store_id');
            $table->unsignedInteger('floor_id');
            $table->string('name', 30);
            $table->unsignedInteger('sequence')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('store_racks', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('factory_id');
            $table->unsignedInteger('store_id');
            $table->unsignedInteger('floor_id');
            $table->unsignedInteger('room_id');
            $table->string('name', 30);
            $table->unsignedInteger('sequence')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('store_shelves', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('factory_id');
            $table->unsignedInteger('store_id');
            $table->unsignedInteger('floor_id');
            $table->unsignedInteger('room_id');
            $table->unsignedInteger('rack_id');
            $table->string('name', 30);
            $table->unsignedInteger('sequence')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('store_bins', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('factory_id');
            $table->unsignedInteger('store_id');
            $table->unsignedInteger('floor_id');
            $table->unsignedInteger('room_id');
            $table->unsignedInteger('rack_id');
            $table->unsignedInteger('shelf_id');
            $table->string('name', 30);
            $table->unsignedInteger('sequence')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_floors');
        Schema::dropIfExists('store_rooms');
        Schema::dropIfExists('store_racks');
        Schema::dropIfExists('store_shelves');
        Schema::dropIfExists('store_bins');
    }
}
