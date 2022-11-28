<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnInHrEmployee extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hr_employees', function (Blueprint $table) {

            /* null able old column */

            $table->integer('department')->nullable()->change();
            $table->integer('section')->nullable()->change();
            $table->integer('designation')->nullable()->change();
            $table->string('code')->nullable()->change();
            $table->string('type')->nullable()->change();

            $table->string('name_bn')->after('last_name')->nullable();
            $table->string('father_name_bn')->after('father_name')->nullable();
            $table->string('mother_name_bn')->after('mother_name')->nullable();
            $table->string('nominee')->after('mother_name_bn')->nullable();
            $table->string('nominee_bn')->after('nominee')->nullable();
            $table->string('nominee_relation')->after('nominee_bn')->nullable();
            $table->string('nominee_relation_bn')->after('nominee_relation')->nullable();
            $table->string('nationality')->after('nominee_relation_bn')->nullable();
            $table->string('nationality_bn')->after('nationality')->nullable();
            $table->string('permanent_address_bn')->after('permanent_address')->nullable();
            $table->string('zilla')->after('permanent_address_bn')->nullable();
            $table->string('zilla_bn')->after('zilla')->nullable();
            $table->string('upazilla')->after('zilla_bn')->nullable();
            $table->string('upazilla_bn')->after('upazilla')->nullable();
            $table->string('post_code')->after('upazilla_bn')->nullable();
            $table->string('post_code_bn')->after('post_code')->nullable();
            $table->text('present_address_bn')->after('present_address')->nullable();
            $table->text('blood_group')->after('present_address_bn')->nullable();
            $table->string('birth_certificate_no')->after('blood_group')->nullable();
            $table->string('acne_details')->after('birth_certificate_no')->nullable();
            $table->string('acne_details_bn')->after('acne_details')->nullable();
            $table->string('height')->after('acne_details_bn')->nullable();
            $table->string('lawful_guardian')->after('height')->nullable();
            $table->string('lawful_guardian_bn')->after('lawful_guardian')->nullable();
            $table->string('religion')->after('lawful_guardian_bn')->nullable();
            $table->string('religion_bn')->after('religion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hr_employees', function (Blueprint $table) {

        });
    }
}
