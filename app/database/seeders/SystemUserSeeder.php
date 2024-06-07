<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SystemUserSeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\User::factory()
            ->afterCreating(function (\App\Models\User $user) {
                $superAdmin = \App\Models\Role::where('superadmin', true)->first();

                $user->roles()->attach($superAdmin->id, ['owner' => $user->id, 'created_at' => now()]);
                \App\Models\Role::where('owner', null)->update(['owner' => $user->id]);
            })
            ->create([
                'name' => 'SYSTEM',
                'email' => 'system@localhost',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'email_verified_at' => date("Y-m-d H:i:s"),
                'active' => true,
                'system_role' => true,
            ]);
    }
}
