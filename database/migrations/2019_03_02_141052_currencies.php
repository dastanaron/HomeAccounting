<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Currencies extends Migration
{
    private $tableName = 'currencies';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->string('external_id', 55);
            $table->integer('num_code');
            $table->string('char_code', 20);
            $table->integer('nominal')->nullable();
            $table->string('name', 255)->nullable();
            $table->float('value', 10, 4);

            $table->foreign('user_id')
                ->references('id')->on('users')->onDelete('cascade');
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
/*
 *    +CBRFID: "R01820"
    +numCode: 392
    +charCode: "JPY"
    +nominal: 100
    +name: "Японских иен"
    +value: 58.8128

 */