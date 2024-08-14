<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentTable extends Migration
{
    public function up()
    {
        Schema::create('test_eloise_comment', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->foreignId('post_id')->constrained('test_eloise_post')->onDelete('cascade');
            $table->text('body');
            $table->timestamps();
        });

        Schema::table('test_eloise_comment', static function (Blueprint $table): void {
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('test_eloise_comment');
    }
}
