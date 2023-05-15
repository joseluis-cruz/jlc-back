<?php

namespace Database\Seeders;

use App\Models\App;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $app = new App();
        $app->code = "cifp.cuadernos";
        $app->name = "Cuadernos";
        $app->description = "Procesamiento de cuadernos del profesor para seguimiento de programaciones didácticas";
        $app->url_main = "http://cuadernos.cifpcarlos3.net:10443";
        $app->url_get_auth = "http://cuadernos.cifpcarlos3.net:10443/login";
        $app->url_redirect = "http://cuadernos.cifpcarlos3.net:10443/bienvenido";
        $app->token = "C1U2A3D4E5R6N7O8S9";
        $app->save();
    }
}
