<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRingkasanInExtendedDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    protected $connection = 'legatra';
    
    public function up()
    {
        Schema::table('extended_documents', function (Blueprint $table) {
            $table->string('title_ringkasan')->nullable();
            $table->longText('ringkasan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('extended_documents', function (Blueprint $table) {
            //
        });
    }
}
