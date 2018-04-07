<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->string('name');
            $table->string('email');
            $table->string('address');
            $table->string('contact1');
            $table->string('contact2')->nullable();
            $table->string('state_code');
            $table->string('pan_no')->nullable();
            $table->string('gstn_no');
            $table->string('acc_name')->nullable();
            $table->string('acc_no')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->string('branch')->nullable();
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
        Schema::dropIfExists('suppliers');
    }
}
