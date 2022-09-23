<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Faker\Factory as Faker;
use App\Models\Config;
use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $this->createDepartments();
        $this->createConfigs();

        $this->createRootData();

        for ($i = 1; $i <= 5; $i++) {
            User::query()->create([
                'email' => $faker->email,
                'name' => $faker->name,
            ]);
        }



    }

    public function createRootData(): void
    {
        $user = User::query()->create([
            'email' => 'feature451@gmail.com',
            'name' => 'Bui Huu Loc',
        ]);
        $department_ids = Department::query()->get()->pluck('id')->toArray();
        $user->subscribedDepartments()->attach($department_ids);
    }

    public function createConfigs(): void
    {
        Config::query()->insert([
            [
                'id' => Str::uuid(),
                'group' => 'mail_notification',
                'key' => 'sender',
                'value' => 'Thông báo từ TDTU',
            ],
            [
                'id' => Str::uuid(),
                'group' => 'mail_notification',
                'key' => 'header_logo',
                'value' => 'https://i.pinimg.com/564x/49/be/ae/49beae1cdacd77f3459d9c5d6ff5555c.jpg',
            ],
            [
                'id' => Str::uuid(),
                'group' => 'mail_notification',
                'key' => 'footer_logo',
                'value' => 'https://i.pinimg.com/564x/de/86/29/de8629935b90d646b825e2adbfce0395.jpg',
            ],
        ]);
    }

    public function createDepartments(): void
    {
        Department::query()->insert([
            [
                'id' => Str::uuid(),
                'unit_id' => '5',
            ],
            [
                'id' => Str::uuid(),
                'unit_id' => 'B',
            ],
            [
                'id' => Str::uuid(),
                'unit_id' => 'G',
            ],
            [
                'id' => Str::uuid(),
                'unit_id' => 'P02',
            ],
            [
                'id' => Str::uuid(),
                'unit_id' => 'P03',
            ],
            [
                'id' => Str::uuid(),
                'unit_id' => 'P04',
            ],
            [
                'id' => Str::uuid(),
                'unit_id' => 'P07',
            ],
            [
                'id' => Str::uuid(),
                'unit_id' => 'P09',
            ],
            [
                'id' => Str::uuid(),
                'unit_id' => 'P12',
            ],
            [
                'id' => Str::uuid(),
                'unit_id' => 'P15',
            ],
            [
                'id' => Str::uuid(),
                'unit_id' => 'P27',
            ],
            [
                'id' => Str::uuid(),
                'unit_id' => 'P35',
            ],
            [
                'id' => Str::uuid(),
                'unit_id' => 'P48',
            ],

        ]);
    }
}
