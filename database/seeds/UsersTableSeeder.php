<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();

        $adminRole = Role::where('name', 'admin')->first();
        $vendorRole = Role::where('name', 'vendor')->first();
        $buyerRole = Role::where('name', 'buyer')->first();

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'superadmin@gmail.com',
            'password' => bcrypt('qweqwe123')
        ]);

        $vendor = User::create([
            'name' => 'Vendor',
            'email' => 'puno.roni@gmail.com',
            'password' => bcrypt('qweqwe123')
        ]);

        $buyer = User::create([
            'name' => 'Buyer',
            'email' => 'admin@admin.com',
            'password' => bcrypt('qweqwe123')
        ]);

        $admin->roles()->attach($adminRole);
        $vendor->roles()->attach($vendorRole);
        $buyer->roles()->attach($buyerRole);
    }
}
