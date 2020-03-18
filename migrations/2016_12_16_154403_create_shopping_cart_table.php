<?php

/*
 * This file is part of ibrand/laravel-shopping-cart.
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
        if (!Schema::hasTable('shopping_cart')) {

            Schema::create('shopping_cart', function (Blueprint $table) {
                $table->string('key');
                $table->string('__raw_id');
                $table->string('guard')->nullable();
                $table->integer('user_id')->nullable();
                $table->integer('id');
                $table->string('name');
                $table->integer('qty');
                $table->decimal('price');
                $table->decimal('total');
                $table->string('__model')->nullable();
                $table->string('type')->nullable();
                $table->string('status')->nullable();
                $table->text('attributes')->nullable();
                $table->primary(['key', '__raw_id']);
                $table->nullableTimestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('shopping_cart');
    }
}
