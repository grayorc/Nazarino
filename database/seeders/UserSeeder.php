<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Follower;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create multiple users
        $users = [
            [
                'first_name' => 'علی',
                'last_name' => 'احمدی',
                'username' => 'ali_ahmadi',
                'email' => 'ali.ahmadi@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'first_name' => 'فاطمه',
                'last_name' => 'محمدی',
                'username' => 'fatemeh_mohammadi',
                'email' => 'fatemeh.mohammadi@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'first_name' => 'محمد',
                'last_name' => 'رضایی',
                'username' => 'mohammad_rezaei',
                'email' => 'mohammad.rezaei@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'first_name' => 'زهرا',
                'last_name' => 'حسینی',
                'username' => 'zahra_hosseini',
                'email' => 'zahra.hosseini@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'first_name' => 'امیر',
                'last_name' => 'کریمی',
                'username' => 'amir_karimi',
                'email' => 'amir.karimi@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'first_name' => 'نرگس',
                'last_name' => 'جعفری',
                'username' => 'narges_jafari',
                'email' => 'narges.jafari@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'first_name' => 'حسین',
                'last_name' => 'محمودی',
                'username' => 'hossein_mahmoudi',
                'email' => 'hossein.mahmoudi@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'first_name' => 'مریم',
                'last_name' => 'نوری',
                'username' => 'maryam_nouri',
                'email' => 'maryam.nouri@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'first_name' => 'رضا',
                'last_name' => 'صادقی',
                'username' => 'reza_sadeghi',
                'email' => 'reza.sadeghi@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'first_name' => 'سارا',
                'last_name' => 'فرهادی',
                'username' => 'sara_farahadi',
                'email' => 'sara.farahadi@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'first_name' => 'امیرحسین',
                'last_name' => 'طاهری',
                'username' => 'amirhossein_taheri',
                'email' => 'amirhossein.taheri@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'first_name' => 'الهام',
                'last_name' => 'بهرامی',
                'username' => 'elham_bahrami',
                'email' => 'elham.bahrami@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'first_name' => 'پویا',
                'last_name' => 'میرزایی',
                'username' => 'pouya_mirzaei',
                'email' => 'pouya.mirzaei@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'first_name' => 'شیرین',
                'last_name' => 'قاسمی',
                'username' => 'shirin_ghasemi',
                'email' => 'shirin.ghasemi@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'first_name' => 'مهدی',
                'last_name' => 'یزدانی',
                'username' => 'mahdi_yazdani',
                'email' => 'mahdi.yazdani@example.com',
                'password' => Hash::make('password'),
            ],
        ];

        // Create users and make them follow seedadmin (user ID 1)
        foreach ($users as $userData) {
            $user = User::create($userData);

            // Make this user follow seedadmin (user ID 1)
            Follower::create([
                'user_id' => 1, // seedadmin's ID
                'follower_id' => $user->id,
            ]);
        }
    }
}
