<?php

namespace Database\Seeders;

use App\Models\Key;
use App\Models\Template;
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
        //  \App\Models\MailConfig::factory(1)->create();

        /*$forgotpassword = Template::create([
            'name' => 'Forgot Password',
            'body' => 'Your password is: {password}',
            'subject' => 'Forgot Password'
        ]);

        $key1 = Key::create([
            'template_id' => $forgotpassword->id,
            'words'       => '{name}'
        ]);
        $key2 = Key::create([
            'template_id' => $forgotpassword->id,
            'words'       => '{email}'
        ]);
        $key3 = Key::create([
            'template_id' => $forgotpassword->id,
            'words'       => '{password}'
        ]);*/

        $welcome = Template::create([
            'name' => 'Welcome',
            'body' => 'Welcome to our website: {name}',
            'subject' => 'Welcome'
        ]);
        $key1 = Key::create([
            'template_id' => $welcome->id,
            'words'       => '{name}'
        ]);
    }
}
