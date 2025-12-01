<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
  public function run()
  {
    DB::table('roles')->insert([
      ['name' => 'Admin', 'description' => 'System administrator'],
      ['name' => 'Teacher', 'description' => 'Handles attendance'],
      ['name' => 'Student', 'description' => 'Can view attendance'],
    ]);
  }
}
