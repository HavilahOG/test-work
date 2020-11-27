<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Models\Price;
use App\Models\Product;
use Illuminate\Console\Command;

class GetPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:price {--codes=} {--id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Returns a product sku and product price.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $codes = $this->option('codes') ?? $this->ask('Please provide a product code or codes separated by ","');
        if(is_null($codes)){
            $this->error('Product code required!');
            return $this->handle();
        }

        $products = Product::whereIn('sku', explode(',', $codes))->get();


        $account =$this->account();

        ////DLWWXS,REDKNN
        $data = [];
        if(empty($account)){
            foreach ($products as $product) {
                $price = Price::whereProductId($product->id)->whereNull('account_id')->min('value');
                $data[] = ['sku' => $product->sku, 'price' => $price];
            }
        }else{
            foreach ($products as $product) {
                $price = Price::whereProductId($product->id)->whereAccountId($account->id)->min('value');
                $data[] = ['sku' => $product->sku, 'price' => $price];
            }
        }

        $this->table( ['SKU', 'PRICE'], $data);

    }

    public function account()
    {
        if ($this->confirm('Would you like to provide an account ID?')) {
            $id = $this->option('id') ?? $this->ask('Please provide an account ID.');

            $account = Account::whereId($id)->first();
            if(is_null($account)){
                $this->error('Account not found!');
                return $this->account();
            }

            return $account;
        }
    }
}
