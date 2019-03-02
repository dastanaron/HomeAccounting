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
            $table->string('external_id', 55)->unique();
            $table->integer('num_code')->unique();
            $table->string('char_code', 20)->unique();
            $table->integer('nominal')->nullable();
            $table->string('name', 255)->nullable();
            $table->float('value', 10, 4);

            $table->primary('num_code');

        });

        Schema::table('bills', function (Blueprint $table) {
            $table->integer('currency')->default(643)->nullable()->after('sum');
            $table->index('currency');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('bills', function (Blueprint $table) {
            $table->dropColumn('currency');
        });

        Schema::dropIfExists($this->tableName);
    }
}