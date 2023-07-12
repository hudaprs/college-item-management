<?php

use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert([
            [
                'name' => 'Sabun Mandi',
                'description' => 'Sabun untuk mandi',
                'image' => null,
                'stock' => 10,
                'price' => 4500,
            ],
            [
                'name' => 'Dettol',
                'description' => 'Sabun untuk gatal-gatal',
                'image' => null,
                'stock' => 10,
                'price' => 10000,
            ],
            [
                'name' => 'Shampo Clear',
                'description' => 'Sabun yang disponsori Ronaldo',
                'image' => null,
                'stock' => 100,
                'price' => 13500,
            ]
        ]);
    }
}