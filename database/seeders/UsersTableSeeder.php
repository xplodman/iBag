<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Admin',
                'email' => 'admin@ibag.localhost',
                'email_verified_at' => NULL,
                'otp' => NULL,
                'password' => '$2y$12$lAg5L1fTJe9xOaA1JyLKBOeqdcrH2rTwHRFZVC7/ynlwoj52vc8EK',
                'two_factor_secret' => NULL,
                'two_factor_recovery_codes' => NULL,
                'two_factor_confirmed_at' => NULL,
                'remember_token' => '3sajpYfjYJkRzafMmjhdg6W53kV7P08GLxIUHeGxGPuY46kqqCc62wC2smwR',
                'current_team_id' => NULL,
                'profile_photo_path' => NULL,
                'created_at' => '2024-03-20 23:07:31',
                'updated_at' => '2024-03-27 22:22:51',
                'provider' => NULL,
                'provider_id' => NULL,
                'deleted_at' => NULL,
                'is_provider' => 1,
                'mobile' => NULL,
                'address' => NULL,
            ),
        ));
        
        
    }
}