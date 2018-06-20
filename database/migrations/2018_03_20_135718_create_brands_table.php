<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name');
            $table->string('common_name')->default('');
//            $table->string('img')->default('/img/brands/default.png');
            $table->string('country')->default('美国');
            $table->unsignedTinyInteger('country_id')->default(1)->index();
            $table->string('official_website')->default('');
            $table->string('similar_name')->default('');
//            $table->unsignedSmallInteger('products_count')->default(0);
            $table->unsignedInteger('reviews_count')->default(0)->index();
            $table->unsignedInteger('buys_count')->default(0)->index();
//            $table->unsignedTinyInteger('buys_percent')->default(50)->index();

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
        Schema::dropIfExists('brands');
    }
}
