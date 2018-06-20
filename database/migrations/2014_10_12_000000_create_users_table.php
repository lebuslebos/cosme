<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('avatar')->default(config('app.url').'/avatars/default.jpg');//默认为1号头像，并做缩略图处理
            $table->char('mobile',11)->unique();
            $table->unsignedTinyInteger('skin')->default(2);
            $table->unsignedInteger('reviews_count')->default(0);
            $table->unsignedInteger('buys_count')->default(0);
            $table->unsignedInteger('likes_count')->default(0);
            $table->unsignedInteger('hates_count')->default(0);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
