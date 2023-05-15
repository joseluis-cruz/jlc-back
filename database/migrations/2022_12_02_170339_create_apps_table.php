<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apps', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->string('url_main')->nullable();
            $table->string('url_get_auth')->nullable();
            $table->string('url_redirect')->nullable();
            $table->string('token')->nullable();
            $table->timestamps();
        });

        Schema::create('apps_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('app_id')->constrained('apps');
            $table->foreignId('user_id')->constrained('users');
            $table->text('acceso');
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
        Schema::dropIfExists('apps_users');
        Schema::dropIfExists('apps');
    }
}
