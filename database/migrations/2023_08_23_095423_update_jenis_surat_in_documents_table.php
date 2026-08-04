<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateJenisSuratInDocumentsTable extends Migration
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
            $table->unsignedBigInteger('title_id')->nullable();
            $table->foreign('title_id')->references('id')->on('titles');
            $table->string('document_type', 50)->nullable();
            $table->string('letter_type', 50)->nullable();
            $table->string('letter_purpose', 50)->nullable();
        });

        Schema::table('document_histories', function (Blueprint $table) {
            $table->unsignedBigInteger('title_id')->nullable();
            $table->foreign('title_id')->references('id')->on('titles');
            $table->string('document_type', 50)->nullable();
            $table->string('letter_type', 50)->nullable();
            $table->string('letter_purpose', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('documents', function (Blueprint $table) {
            //
        });
    }
}
