<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubcontractFactoryProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subcontract_factory_profiles', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('operation_type')->comment('1 => Cutting Factory, 2=> Embellishment Factory, 3=> Sewing Factory, 4=> Finishing Factory, 5=> Inspection Factory');
            $table->string('name');
            $table->string('short_name', 30)->nullable();
            $table->string('address')->nullable();
            $table->string('responsible_person');
            $table->string('email', 50)->nullable();
            $table->string('contact_no', 50)->nullable();
            $table->text('remarks')->nullable();
            $table->tinyInteger('status')->default(1)->comment("1=Active,0=Inactive");
            $table->unsignedInteger('factory_id')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
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
        Schema::dropIfExists('subcontract_factory_profiles');
    }
}
