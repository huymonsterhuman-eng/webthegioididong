<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Create post_categories table if it doesn't exist
        if (!Schema::hasTable('post_categories')) {
            Schema::create('post_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->timestamps();
            });

            // Migrate existing string categories from posts only if table was just created
            $existing = \DB::table('posts')
                ->whereNotNull('category')
                ->where('category', '!=', '')
                ->distinct()
                ->pluck('category');

            foreach ($existing as $cat) {
                \DB::table('post_categories')->insertOrIgnore([
                    'name' => $cat,
                    'slug' => \Illuminate\Support\Str::slug($cat) ?: 'uncategorized',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Update posts table
        Schema::table('posts', function (Blueprint $table) {
            if (!Schema::hasColumn('posts', 'post_category_id')) {
                $table->foreignId('post_category_id')->nullable()
                    ->constrained('post_categories')->nullOnDelete()
                    ->after('slug');
            }
            if (!Schema::hasColumn('posts', 'author_id')) {
                $table->unsignedBigInteger('author_id')->nullable()->after('post_category_id');
                $table->foreign('author_id')->references('id')->on('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('posts', 'is_published')) {
                $table->boolean('is_published')->default(false)->after('content');
            }
            if (!Schema::hasColumn('posts', 'published_at')) {
                $table->timestamp('published_at')->nullable()->after('is_published');
            }
            if (!Schema::hasColumn('posts', 'views')) {
                $table->unsignedInteger('views')->default(0)->after('published_at');
            }
        });

        // Backfill post_category_id from the old string column if it exists
        if (Schema::hasColumn('posts', 'category')) {
            $categories = \DB::table('post_categories')->get()->keyBy('name');
            $posts = \DB::table('posts')->whereNotNull('category')->get();
            foreach ($posts as $post) {
                if (isset($categories[$post->category])) {
                    \DB::table('posts')->where('id', $post->id)->update([
                        'post_category_id' => $categories[$post->category]->id,
                    ]);
                }
            }

            // Remove old string category column and redundant date column
            Schema::table('posts', function (Blueprint $table) {
                $table->dropColumn(array_filter(['category', 'date'], function ($col) {
                    return Schema::hasColumn('posts', $col);
                }));
            });
        }
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            if (Schema::hasColumn('posts', 'post_category_id')) {
                $table->dropForeign(['post_category_id']);
            }
            if (Schema::hasColumn('posts', 'author_id')) {
                $table->dropForeign(['author_id']);
            }
            $table->dropColumn(array_filter(['post_category_id', 'author_id', 'is_published', 'published_at', 'views'], function ($col) {
                return Schema::hasColumn('posts', $col);
            }));
            
            if (!Schema::hasColumn('posts', 'category')) {
                $table->string('category')->nullable();
            }
            if (!Schema::hasColumn('posts', 'date')) {
                $table->timestamp('date')->nullable();
            }
        });
        Schema::dropIfExists('post_categories');
    }
};
