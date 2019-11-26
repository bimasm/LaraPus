<?php

use Illuminate\Database\Seeder;

class AnggotasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Anggota::insert([
            [
              'id'  			=> 1,
              'user_id'  		=> 1,
              'npm'				=> 1202170101,
              'nama' 			=> 'Admin',
              'tempat_lahir'	=> 'Bandung',
              'tgl_lahir'		=> '1999-05-06',
              'jk'				=> 'L',
              'prodi'			=> 'SI',
              'created_at'      => \Carbon\Carbon::now(),
              'updated_at'      => \Carbon\Carbon::now()
            ],
            [
              'id'  			=> 2,
              'user_id'  		=> 2,
              'npm'				=> 1202170202,
              'nama' 			=> 'User',
              'tempat_lahir'	=> 'Bandung',
              'tgl_lahir'		=> '1999-01-12',
              'jk'				=> 'L',
              'prodi'			=> 'TI',
              'created_at'      => \Carbon\Carbon::now(),
              'updated_at'      => \Carbon\Carbon::now()
            ],
        ]);
    }
}
