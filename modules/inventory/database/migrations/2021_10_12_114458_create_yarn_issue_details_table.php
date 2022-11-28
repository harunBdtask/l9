<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYarnIssueDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yarn_issue_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('yarn_issue_id');
            $table->string('demand_no', 50)->nullable()->comment('Request./Demand No');
            $table->string('yarn_lot', 30)->comment('Lot No/Yarn Count');
            $table->string('issue_qty', 20)->comment('Issue Qty');
            $table->string('rate', 20)->comment('Issue Qty');
            $table->string('issue_value', 20)->comment('Qty * Rate');
            $table->string('returnable_qty', 20)->nullable();

            $table->unsignedInteger('uom_id');
            $table->unsignedInteger('yarn_count_id');
            $table->unsignedInteger('yarn_composition_id');
            $table->unsignedInteger('yarn_type_id');
            $table->string('yarn_color')->nullable();
            $table->string('dyeing_color')->nullable();

            $table->unsignedInteger('store_id');
            $table->unsignedInteger('floor_id')->nullable();
            $table->unsignedInteger('room_id')->nullable();
            $table->unsignedInteger('rack_id')->nullable();
            $table->unsignedInteger('shelf_id')->nullable();
            $table->unsignedInteger('bin_id')->nullable();

            $table->integer('no_of_bag')->nullable();
            $table->integer('no_of_cone_per_bag')->nullable();
            $table->integer('no_of_cone')->nullable();
            $table->integer('weight_per_bag')->nullable();
            $table->integer('weight_per_cone')->nullable();

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
        Schema::dropIfExists('yarn_issue_details');
    }
}
