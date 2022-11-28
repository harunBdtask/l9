<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email')->unique();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('screen_name')->nullable();
            $table->string('designation')->nullable();
            $table->string('address')->nullable();
            $table->string('phone_no', 15)->nullable();
            $table->unsignedInteger('factory_id');
            $table->unsignedInteger('role_id');
            $table->string('department', 15)->nullable();
            $table->text('profile_image')->nullable();
            $table->text('permissions')->nullable();
            $table->smallInteger('status')->default(0);
            $table->string('password');
            $table->timestamp('last_login')->nullable();
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
