<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeatSeeder extends Seeder
{
    private $zones = [
        'N101', 'N102', 'N103', 'N104', 'N105', 'N106', 'N201', 'N202', 'N203', 'N204', 'N205', 'N206', 'N207',
        'E101', 'E102', 'E103', 'E104', 'E105','E106', 'E107', 'E108', 'E109', 'E201', 'E202', 'E203', 'E204', 'E205','E206', 'E207', 'E208', 'E209', 'E210', 'E211',
        'S101', 'S102', 'S103', 'S104', 'S201', 'S202', 'S203', 'S204', 'S205',
        'V101', 'V102', 'V103', 'V201', 'V202',
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->zones as $zone) {
            if (strpos($zone, '20') !== false || $zone == 'E210' || $zone == 'E211') {
                for ($i = 1; $i <= 520; $i++) {
                    DB::table('seats')->insert([
                        'chair_number' => $i,
                        'zone' => $zone
                    ]);
                }
            }
            else {
                for ($i = 1; $i < 480; $i++) {
                    DB::table('seats')->insert([
                        'chair_number' => $i,
                        'zone' => $zone
                    ]);
                }
            }
        }
    }
}
