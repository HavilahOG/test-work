<?php

namespace Database\Seeders;

use App\Imports\ImportPrices;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $data = Storage::disk('local-storage')->get('import.csv');
        Excel::import(new ImportPrices, 'import.csv', 'local-storage');

    }
}
