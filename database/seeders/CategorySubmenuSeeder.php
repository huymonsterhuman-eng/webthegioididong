<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySubmenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $parent = \App\Models\Category::firstOrCreate(
            ['slug' => 'phu-kien'],
            ['name' => 'Phụ kiện', 'description' => 'Phụ kiện điện thoại, máy tính']
        );

        $subcategories = [
            'Tai nghe',
            'Sạc nhanh',
            'Chuột',
            'Bàn phím',
            'Sạc dự phòng',
            'Loa bluetooth',
        ];

        foreach ($subcategories as $sub) {
            \App\Models\Category::updateOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($sub)],
                [
                    'name' => $sub,
                    'parent_id' => $parent->id,
                    'description' => "Chuyên mục $sub",
                ]
            );
        }
    }
}
