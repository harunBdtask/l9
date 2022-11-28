<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationInquiriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotation_inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('quotation_id')->nullable()->comment = "sid";
            $table->unsignedInteger('factory_id')->nullable();
            $table->unsignedInteger('buyer_id')->nullable();
            $table->string('style_name', 211)->nullable();
            $table->unsignedInteger('garment_item_id')->nullable();
            $table->unsignedInteger('season_id')->nullable();
            $table->tinyInteger('status')->nullable()->comment = "1=Active, 2=Inactive";
            $table->date('inquiry_date')->nullable();
            $table->unsignedInteger('dealing_merchant')->nullable()->comment = "Team Members";
            $table->date('submission_date')->nullable();
            $table->date('approval_date')->nullable();
            $table->tinyInteger('required_sample')->nullable()->comment = "1=Yes, 2=No";
            $table->text('remarks')->nullable();
            $table->text('file_name')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
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
        Schema::dropIfExists('quotation_inquiries');
    }
}
