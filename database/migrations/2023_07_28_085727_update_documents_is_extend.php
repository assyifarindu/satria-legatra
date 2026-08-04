<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDocumentsIsExtend extends Migration
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
            $table->boolean('is_extend');
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
