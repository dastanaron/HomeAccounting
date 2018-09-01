<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CorrectSum extends Migration
{

    public $tableBills = 'bills';
    public $tableFunds = 'funds';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Таблица счетов
        Schema::table($this->tableBills, function (Blueprint $table) {
            $table->float('sum')->change();
        });

        //Таблица движения средств
        Schema::table($this->tableFunds, function (Blueprint $table) {
            $table->float('sum')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->tableBills, function (Blueprint $table) {
            $table->integer('sum')->change();
        });

        Schema::table($this->tableFunds, function (Blueprint $table) {
            $table->integer('sum')->change();
        });
    }
}
