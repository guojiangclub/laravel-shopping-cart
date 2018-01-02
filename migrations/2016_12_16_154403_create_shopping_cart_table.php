<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShoppingCartTable extends Migration
{

	/**
	 * Run the migrations.
	 * 单品折扣表
	 * @return void
	 */
	public function up()
	{
		Schema::create('shopping_cart', function(Blueprint $table) {
            $table->string('key');
            $table->string('__raw_id');
            $table->integer('id');
            $table->string('name');
            $table->integer('qty');
            $table->decimal('price');
            $table->decimal('total');
            $table->string('__model');
            $table->string('type');
			$table->string('status')->nullable();
            $table->text('attributes')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('shopping_cart');
	}

}
