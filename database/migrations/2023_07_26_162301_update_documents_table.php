<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    protected $connection = 'legatra';

    public function up()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->string('company');
            $table->string('serial_number');
            $table->integer('category')->nullable()->change();
            $table->string('priority')->nullable();
            $table->string('pic_nrp')->nullable();
            $table->string('pic_email')->nullable();
            $table->string('pic_name')->nullable();
            $table->integer('updated_by')->nullable();
            $table->unsignedBigInteger('alert_id');
            $table->foreign('alert_id')->references('id')->on('alerts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
