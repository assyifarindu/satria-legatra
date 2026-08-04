<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    protected $connection = 'legatra';
    
    public function up()
    {
        Schema::create('request_documents', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('title');
            $table->string('para_pihak');
            $table->string('scope');
            $table->string('email');
            $table->string('status');
            $table->string('file');
            $table->integer('created_by');
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
        Schema::dropIfExists('request_documents');
    }
}
