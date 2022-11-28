<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiSessionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_session', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();            
            $table->string("api_key")->unique()->nullable();
            $table->string("status",1);
            $table->timestamps();
        });

        Schema::create('api_session_env', function (Blueprint $table) {
            $table->id();
            $table->foreignId('api_session_id')->constrained('api_session');            
            $table->string("var_name")->nullable();
            $table->string("var_value_str")->nullable();
            $table->integer("var_value_int")->nullable();
            $table->double("var_value_dob")->nullable();
            $table->dateTime("var_value_dtm")->nullable();
            $table->boolean("var_value_boo")->nullable();
            $table->timestamps();
            $table->index(['api_session_id', 'var_name'],'idx_api_session_id_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('api_session_env');
        Schema::dropIfExists('api_session');
    }
}
