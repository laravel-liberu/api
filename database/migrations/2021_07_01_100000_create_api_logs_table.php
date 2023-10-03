<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('api_logs', function (Blueprint $table) {
            $table->increments('id');

            $table->foreignId('user_id')->nullable()->constrained('users')->index()->name('api_logs_user_id_foreign');

            $table->string('route')->nullable();
            $table->string('url');
            $table->string('method');

            $table->integer('status')->index();
            $table->integer('try')->nullable();
            $table->tinyInteger('type')->index();

            $table->unsignedDecimal('duration', 5, 2);

            $table->timestamps();

            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('api_logs');
    }
};
