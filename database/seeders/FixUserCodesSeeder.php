<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class FixUserCodesSeeder extends Seeder
{
    public function run(): void
    {
        // Disabled: user_code logic removed due to missing column
        // foreach (['admin' => 'AD', 'manager' => 'HM', 'customer' => 'CUS'] as $role => $prefix) {
        //     $users = User::where('role', $role)->orderBy('id')->get();
        //     $i = 1;
        //     foreach ($users as $user) {
        //         $user->user_code = $prefix . str_pad($i, 4, '0', STR_PAD_LEFT);
        //         $user->save();
        //         $i++;
        //     }
        // }
    }
}
