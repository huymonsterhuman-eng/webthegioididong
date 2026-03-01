<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Post;

class PostsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            Post::create([
                'title' => "Tin công nghệ hot: Top smartphone đáng mua nhất năm 2026 - Phần $i",
                'slug' => Str::slug("top-smartphone-2026-phan-$i"),
                'image' => 'img/placeholder.jpg',
                'excerpt' => 'Khám phá ngay danh sách những mẫu điện thoại thông minh nổi bật nhất đầu năm 2026 với cấu hình khủng và giá tốt.',
                'content' => 'Nội dung chi tiết của bài viết số ' . $i . '...',
                'date' => now(),
                'category' => 'Tin mới',
            ]);
        }
    }
}
