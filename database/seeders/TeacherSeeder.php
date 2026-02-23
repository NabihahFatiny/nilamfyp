<?php

namespace Database\Seeders;

use App\Models\Teacher;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    /**
     * Seed a default teacher so you can log in to the teacher dashboard.
     * Email: teacher@nilam.test  Password: password
     */
    public function run(): void
    {
        if (Teacher::where('email', 'teacher@nilam.test')->exists()) {
            return;
        }

        Teacher::create([
            'name' => 'Default Teacher',
            'email' => 'teacher@nilam.test',
            'password' => 'password',
        ]);
    }
}
