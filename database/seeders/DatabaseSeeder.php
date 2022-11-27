<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Period;
use App\Models\Promotion;
use App\Models\Semester;
use App\Models\Setting;
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
        $this->createPeriods();

        Config::query()->create([
            ['id' => Str::uuid(), 'group' =>  'start_study_week', 'key' => 'semester_1', 'value' => '1'],
            ['id' => Str::uuid(), 'group' =>  'start_study_week', 'key' => 'semester_2', 'value' => '21'],
            ['id' => Str::uuid(), 'group' =>  'start_study_week', 'key' => 'semester_3', 'value' => '45'],
        ]);
        $default = [
            'start_date' => '15-08-2022',
            'end_date' => '20-08-2023',
            'semester_1_start_date' => '15-08-2022',
            'semester_2_start_date' => '02-01-2023',
            'semester_3_start_date' => '19-06-2023',
        ];
        (new \App\Http\Controllers\ConfigController())->createStudyPlan(null, $default);

    }

    public function createRootData(): void
    {
        User::query()->insert([
            'id' => User::MASTER_ID,
            'email' => '521H0504@student.tdtu.edu.vn',
            'name' => 'Bui Huu Loc',
            'role' => 3,
            'active' => 1,
        ]);
        $user = User::query()->first();
        Setting::query()->create([
            'key' => 'theme',
            'value' => 'light',
            'user_id' => $user->id,
        ]);
        Setting::query()->create([
            'key' => 'auto_read_notification',
            'value' => 1,
            'user_id' => $user->id,
        ]);
        Promotion::query()->insert([
            [
                'id' => 'id-code-1',
                'code' => 'ma-code-1',
                'status' => 1,
                'user_id' => $user->id,
            ],
            [
                'id' => 'id-code-2',
                'code' => 'ma-code-2',
                'status' => 0,
                'user_id' => $user->id,
            ],
            [
                'id' => 'id-code-3',
                'code' => 'ma-code-3',
                'status' => null,
                'user_id' => $user->id,
            ],
        ]);
        $department_ids = Department::query()->get()->pluck('id')->toArray();
        $user->subscribedDepartments()->attach($department_ids);
    }

    public function createPeriods(): void
    {
        $data = [
            [
                'id' => Str::uuid(),
                'period' => 1,
                'started_ed' => '06:50',
                'ended_at' => '07:40',
            ],
            [
                'id' => Str::uuid(),
                'period' => 2,
                'started_ed' => '07:40',
                'ended_at' => '08:30',
            ],
            [
                'id' => Str::uuid(),
                'period' => 3,
                'started_ed' => '08:30',
                'ended_at' => '09:20',
            ],
            [
                'id' => Str::uuid(),
                'period' => 4,
                'started_ed' => '9:30',
                'ended_at' => '10:20',
            ],
            [
                'id' => Str::uuid(),
                'period' => 5,
                'started_ed' => '10:20',
                'ended_at' => '11:10',
            ],
            [
                'id' => Str::uuid(),
                'period' => 6,
                'started_ed' => '11:10',
                'ended_at' => '12:00',
            ],
            [
                'id' => Str::uuid(),
                'period' => 7,
                'started_ed' => '12:45',
                'ended_at' => '13:35',
            ],
            [
                'id' => Str::uuid(),
                'period' => 8,
                'started_ed' => '13:35',
                'ended_at' => '14:25',
            ],
            [
                'id' => Str::uuid(),
                'period' => 9,
                'started_ed' => '14:25',
                'ended_at' => '15:15',
            ],
            [
                'id' => Str::uuid(),
                'period' => 10,
                'started_ed' => '15:25',
                'ended_at' => '16:15',
            ],
            [
                'id' => Str::uuid(),
                'period' => 11,
                'started_ed' => '16:15',
                'ended_at' => '17:05',
            ],
            [
                'id' => Str::uuid(),
                'period' => 12,
                'started_ed' => '17:05',
                'ended_at' => '17:55',
            ],
            [
                'id' => Str::uuid(),
                'period' => 13,
                'started_ed' => '18:05',
                'ended_at' => '18:55',
            ],
            [
                'id' => Str::uuid(),
                'period' => 14,
                'started_ed' => '18:55',
                'ended_at' => '19:45',
            ],
            [
                'id' => Str::uuid(),
                'period' => 15,
                'started_ed' => '19:45',
                'ended_at' => '20:35',
            ],
        ];
        Period::query()->insert($data);
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
                'type' => Department::FACULTY,
            ],
            [
                'id' => Str::uuid(),
                'unit_id' => 'B',
                'type' => Department::FACULTY,
            ],
            [
                'id' => Str::uuid(),
                'unit_id' => 'G',
                'type' => Department::POPULAR,
            ],
            [
                'id' => Str::uuid(),
                'unit_id' => 'P02',
                'type' => Department::POPULAR,
            ],
            [
                'id' => Str::uuid(),
                'unit_id' => 'P03',
                'type' => Department::POPULAR,
            ],
            [
                'id' => Str::uuid(),
                'unit_id' => 'P04',
                'type' => Department::POPULAR,
            ],
            [
                'id' => Str::uuid(),
                'unit_id' => 'P07',
                'type' => Department::POPULAR,
            ],
            [
                'id' => Str::uuid(),
                'unit_id' => 'P09',
                'type' => Department::POPULAR,
            ],
            [
                'id' => Str::uuid(),
                'unit_id' => 'P12',
                'type' => Department::POPULAR,
            ],
            [
                'id' => Str::uuid(),
                'unit_id' => 'P15',
                'type' => Department::POPULAR,
            ],
            [
                'id' => Str::uuid(),
                'unit_id' => 'P27',
                'type' => Department::POPULAR,
            ],
            [
                'id' => Str::uuid(),
                'unit_id' => 'P35',
                'type' => Department::POPULAR,
            ],
            [
                'id' => Str::uuid(),
                'unit_id' => 'P48',
                'type' => Department::POPULAR,
            ],
        ]);
        for($i = 2; $i <= 50; $i++) {
            if (in_array($i, [5, 6, 8, 10, 11, 13, 14, 16, 17, 18, 19, 20, 21, 22, 26, 28, 29, 30, 31, 32, 33, 34,
                36, 37, 38, 39, 42, 43, 44, 45, 46, 47, 50,]))
            {
                Department::query()->firstOrCreate(
                    [
                        'unit_id' => 'P' . ($i < 10 ? '0' . $i : $i),
                    ],
                    [
                        'unit_id' => 'P' . ($i < 10 ? '0' . $i : $i),
                        'type' => Department::OTHER,
                    ]
                );
            }
        }
        foreach (['A', 'C', 'D', 'E', 'F', 'H', 'I', 'K', '0', '1', '2', '3', '4', '6', '7', '8', '9',] as $faculty) {
            Department::query()->create([
                'unit_id' => $faculty,
                'type' => Department::FACULTY,
            ]);
        }
    }
}
