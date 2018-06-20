<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('cat_id')->index();
            $table->unsignedSmallInteger('brand_id')->index();
            $table->string('name');
            $table->string('common_name')->default('');
            $table->string('nick_name')->default('');
//            $table->string('img')->default('/img/products/default.png');
            $table->double('rate',2,1)->default(4.0);
            $table->unsignedInteger('reviews_count')->default(0)->index();
//            $table->unsignedTinyInteger('buys_percent')->default(50);
            $table->unsignedInteger('buys_count')->default(0)->index();
            $table->boolean('has_login_review')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
