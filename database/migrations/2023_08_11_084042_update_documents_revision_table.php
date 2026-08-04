<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDocumentsRevisionTable extends Migration
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
            $table->integer('revision')->nullable();
        });

        Schema::table('document_histories', function (Blueprint $table) {
            $table->integer('revision')->nullable();
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
