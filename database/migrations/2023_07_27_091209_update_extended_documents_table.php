<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateExtendedDocumentsTable extends Migration
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
            $table->dropConstrainedForeignId('document_id');
        });

        Schema::table('extended_documents', function (Blueprint $table) {
            $table->integer('document_id');
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
