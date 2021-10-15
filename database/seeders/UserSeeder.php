<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name'      =>  'escribano.test@acme.com',
            'email'     =>  'escribano.test@acme.com',
            'password'  =>  bcrypt('grupo11'),
            'email_verified_at' => Carbon::now()
        ]);
    }
}
