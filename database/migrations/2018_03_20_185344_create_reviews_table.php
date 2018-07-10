<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable()->index();
            $table->unsignedInteger('product_id')->index();
            $table->unsignedTinyInteger('cat_id')->index();
            $table->unsignedSmallInteger('brand_id')->index();
            $table->unsignedTinyInteger('rate')->default(4)->index();
            $table->unsignedTinyInteger('buy')->default(0)->index();
            $table->unsignedTinyInteger('shop')->default(0)->index();
            $table->unsignedInteger('likes_count')->default(0);
            $table->unsignedInteger('hates_count')->default(0);
            $table->string('device')->default('');
            $table->string('province')->default('');
            $table->string('city')->default('');
            $table->text('body')->nullable();
            $table->string('imgs',1024)->default('[]');
            $table->timestamps();
            $table->unique(['user_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reviews');
    }
}
