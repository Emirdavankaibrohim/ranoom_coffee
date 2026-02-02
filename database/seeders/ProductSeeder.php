<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            'Coffee' => [
                [
                    'name' => 'Americano',
                    'description' => 'Espresso with hot water',
                    'qty' => 100,
                    'image' => 'americano.jpg',
                    'sizes' => [
                        ['size' => 'Small', 'price' => 15000],
                        ['size' => 'Medium', 'price' => 18000],
                        ['size' => 'Large', 'price' => 20000],
                    ]
                ],
                [
                    'name' => 'Latte',
                    'description' => 'Espresso with steamed milk',
                    'qty' => 100,
                    'image' => 'latte.jpg',
                    'sizes' => [
                        ['size' => 'Small', 'price' => 20000],
                        ['size' => 'Medium', 'price' => 25000],
                        ['size' => 'Large', 'price' => 28000],
                    ]
                ],
                [
                    'name' => 'Cappuccino',
                    'description' => 'Espresso with milk foam',
                    'qty' => 100,
                    'image' => 'cappuccino.jpg',
                    'sizes' => [
                        ['size' => 'Small', 'price' => 22000],
                        ['size' => 'Medium', 'price' => 26000],
                        ['size' => 'Large', 'price' => 30000],
                    ]
                ],
            ],
            'Non-Coffee' => [
                [
                    'name' => 'Matcha Latte',
                    'description' => 'Premium Japanese Matcha with milk',
                    'qty' => 50,
                    'image' => 'matcha.jpg',
                    'sizes' => [
                        ['size' => 'Medium', 'price' => 25000],
                        ['size' => 'Large', 'price' => 30000],
                    ]
                ],
                [
                    'name' => 'Chocolate',
                    'description' => 'Rich Belgian Chocolate',
                    'qty' => 50,
                    'image' => 'chocolate.jpg',
                    'sizes' => [
                        ['size' => 'Medium', 'price' => 24000],
                        ['size' => 'Large', 'price' => 28000],
                    ]
                ],
            ],
            'Snack' => [
                [
                    'name' => 'Croissant',
                    'description' => 'Buttery French pastry',
                    'qty' => 30,
                    'image' => 'croissant.jpg',
                    'sizes' => [
                        ['size' => 'Medium', 'price' => 18000],
                    ]
                ],
            ]
        ];

        foreach ($products as $categoryName => $items) {
            $category = Category::where('name', $categoryName)->first();
            
            if (!$category) {
                $category = Category::create(['name' => $categoryName]);
            }

            foreach ($items as $item) {
                $product = Product::firstOrCreate(
                    ['name' => $item['name']],
                    [
                        'description' => $item['description'],
                        'qty' => $item['qty'],
                        'image' => $item['image'], // Ensure these exist or placeholder
                        'category_id' => $category->id,
                    ]
                );

                foreach ($item['sizes'] as $sizeData) {
                    ProductSize::updateOrCreate(
                        [
                            'product_id' => $product->id,
                            'size' => $sizeData['size']
                        ],
                        [
                            'price' => $sizeData['price']
                        ]
                    );
                }
            }
        }
    }
}
