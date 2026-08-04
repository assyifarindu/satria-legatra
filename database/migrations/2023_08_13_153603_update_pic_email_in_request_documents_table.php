<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePicEmailInRequestDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    protected $connection = 'legatra';
    
    public function up()
    {
        Schema::table('request_documents', function (Blueprint $table) {
            $table->unsignedBigInteger('pic_id')->nullable();
            $table->foreign('pic_id')->references('id')->on('pics');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('request_documents', function (Blueprint $table) {
            //
        });
    }
}
