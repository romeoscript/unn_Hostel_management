<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Floor;
use App\Models\Hostel;
use App\Models\Room;
use App\Models\Space;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Example user factory
        \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'reg_no' => '1234522167890',
        ]);

        // Seed specific hostel data
        $hostelData = [
            [
                'name' => 'Nkurumah',
                'image' => 'greenfield.jpg',
                'type' => 1,
                'floors' => 3,
                'rooms_per_floor' => 10,
                'spaces_per_room' => 3,
            ],
            [
                'name' => 'Nkanu Hostel',
                'image' => 'sunset-villa.jpg',
                'type' => 0, // Example type
                'floors' => 2,
                'rooms_per_floor' => 8,
                'spaces_per_room' => 2,
            ],
            // Add more hostels here as needed
        ];

        foreach ($hostelData as $data) {
            // Check if the hostel already exists using 'firstOrCreate'
            $hostel = Hostel::firstOrCreate(
                ['name' => $data['name']],  // Conditions to check existing record
                ['image' => $data['image'], 'type' => $data['type']] // Values if not found
            );

            // Only create floors, rooms, and spaces if the hostel is new
            if ($hostel->wasRecentlyCreated) {
                for ($a = 1; $a <= $data['floors']; $a++) {
                    $floor = new Floor();
                    $floor->hostel_id = $hostel->id;
                    $floor->save();

                    // Create rooms for each floor
                    for ($j = 1; $j <= $data['rooms_per_floor']; $j++) {
                        $room = new Room();
                        $room->num = $floor->id . rand(20, 99);
                        $room->price = '85000'; // Change the price if needed
                        $room->floor_id = $floor->id;
                        $room->save();

                        // Create spaces for each room
                        for ($k = 1; $k <= $data['spaces_per_room']; $k++) {
                            $space = new Space();
                            $space->name = 'Space ' . $k; // or use a random generator
                            $space->room_id = $room->id;
                            $space->save();
                        }
                    }
                }
            }
        }

        // Create a super admin only if it doesn't exist
        $admin = Admin::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Super Admin', 'password' => Hash::make('password')]
        );
    }
}


