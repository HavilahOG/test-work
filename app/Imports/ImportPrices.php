<?php

namespace App\Imports;

use App\Models\Price;
use App\Models\User;
use App\Models\Product;
use App\Models\Account;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ImportPrices implements ToModel, WithChunkReading, WithBatchInserts, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        //the actual id isn't provided so the 3 queries is necessary
        return new Price([
            'product_id' => Product::whereSku($row[0])->value('id'),
            'account_id' => Account::whereExternalReference($row[1])->value('id'),
            'user_id' => User::whereExternalReference($row[2])->value('id'),
            'quantity' => $row[3],
            'value' => $row[4],
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function batchSize(): int
    {
        return 500;
    }
}
