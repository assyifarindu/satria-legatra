<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParaPihaksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    protected $connection = 'legatra';
    
    public function up()
    {
        Schema::create('para_pihaks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_document_id')->nullable();
            $table->foreign('request_document_id')->references('id')->on('request_documents');
            $table->string('name')->nullable();
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
        Schema::dropIfExists('para_pihaks');
    }
}
