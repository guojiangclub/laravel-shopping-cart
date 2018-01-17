<?php

/*
 * This file is part of ibrand/laravel-sms.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateShoppingCartTable extends Migration
{
    /**
     * Run the migrations.
     * 单品折扣表.
     */
    public function up()
    {
        Schema::create('shopping_cart', function (Blueprint $table) {
            $table->increments('id');
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
            $table->index('key');
            $table->index('__raw_id');
            $table->nullableTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('shopping_cart');
    }
}
