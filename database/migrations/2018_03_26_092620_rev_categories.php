<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RevCategories extends Migration
{

    public $tableName = 'rev_categories';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('rev', 2);
            $table->integer('category_id');
            $table->integer('sum');
            $table->string('cause', 255);
            $table->timestamp('date');

            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('users');
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
 * CREATE TABLE IF NOT EXISTS `accounting`.`rev_categories` (
  `id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_rev_categories_1_idx` (`user_id` ASC),
  CONSTRAINT `fk_rev_categories_1`
    FOREIGN KEY (`user_id`)
    REFERENCES `accounting`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
 */
