<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Election;
use App\Models\Option;
use App\Models\User;

class ProgrammingLanguagesElectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'first_name' => 'seedadmin',
            'last_name' => 'seedadmin',
            'username' => 'seedadmin',
            'email' => 'seedadmin@admin.com',
            'password' => 'password',
        ]);

        $election = Election::create([
            'user_id' => $user->id,
            'title' => 'بهترین زبان های برنامه نویسی',
            'description' => 'در این نظرسنجی بهترین زبان‌های برنامه‌نویسی را انتخاب کنید.',
            'has_comment' => true,
            'is_open' => true,
            'is_public' => true,
        ]);

        $options = [
            ['پایتون', 'پایتون یک زبان برنامه نویسی قدرتمند است که در زمینه هوش مصنوعی و هوش مصنوعی استفاده می شود.'],
            ['جاوااسکریپت', 'جاوااسکریپت یک زبان برنامه نویسی قدرتمند است که در زمینه وب و موبایل استفاده می شود.'],
            ['جاوا', 'جاوا یک زبان برنامه نویسی قدرتمند است که در زمینه وب و موبایل استفاده می شود.'],
            ['C#', 'C# یک زبان برنامه نویسی قدرتمند است که در زمینه وب و موبایل استفاده می شود.'],
            ['PHP', 'PHP یک زبان برنامه نویسی قدرتمند است که در زمینه وب و موبایل استفاده می شود.'],
            ['Go', 'Go یک زبان برنامه نویسی قدرتمند است که در زمینه وب و موبایل استفاده می شود.'],
        ];

        foreach ($options as $option) {
            Option::create([
                'election_id' => 1,
                'title' => $option[0],
                'description' => $option[1],
            ]);
        }
    }
}
