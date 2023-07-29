<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_site', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('appid')->index();
            $table->string('host',64)->index();
            $table->string('secret',64)->nullable();
            $table->string('smtp_host',64)->nullable();
            $table->string('smtp_port',16)->nullable();
            $table->string('smtp_encryption',16)->nullable();
            $table->string('smtp_username',64)->nullable();
            $table->string('smtp_password',128)->nullable();
            $table->string('smtp_from_address',64)->nullable();
            $table->string('smtp_from_name',32)->nullable();
            $table->tinyInteger('status')->default(0)->nullable();
            $table->unsignedBigInteger('created_at');
            $table->unsignedBigInteger('updated_at');
            //$table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_site');
    }
};
