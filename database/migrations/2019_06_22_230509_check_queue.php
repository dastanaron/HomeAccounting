<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CheckQueue extends Migration
{
    private $tableName = 'check_queue';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->uuid('uuid');
            $table->unsignedInteger('user_id');
            $table->string('qrcode', 100);
            $table->string('control_sum', 100);
            $table->timestamps();
            $table->primary('uuid');
            $table->unique('control_sum', 'uniq_cs');
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
        Schema::dropIfExists($this->tableName);
    }
}
