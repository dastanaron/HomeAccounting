<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Funds extends Migration
{

    public $tableName = 'funds';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('bills_id');
            $table->tinyInteger('rev');
            $table->unsignedInteger('category_id');
            $table->integer('sum');
            $table->string('cause', 255);
            $table->timestamp('date');

            $table->timestamps();

            $table->foreign('bills_id')
                ->references('id')->on('bills')->onDelete('cascade');

            $table->foreign('category_id')
                ->references('id')->on('rev_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->tableName);
    }
}