<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DefaultCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        // Check if categories table exists
        if (!Schema::hasTable('categories')) {
            $this->command->error('Categories table does not exist!');
            return;
        }

        $categories = [
            'Snacks',
            'Dranken',
            'Broodjes en dürüm',
            'Burgers',
            'Kapsalon',
            'Turkse pizza',
            'Schotels',
            'Mix schotels',
            'Friet',
            "Pizza's",
            'Salades',
            'Uitgelicht',
            "Creëer je eigen Flamin' Wok",
            'Kip specials',
            'Rundvlees specials',
            'Garnalen specials',
            'Vegetarische specials',
        ];

        $createdCount = 0;
        $skippedCount = 0;

        foreach ($categories as $index => $name) {
            $slug = Str::slug($name);

            // Check if category already exists (either by slug or name)
            $existing = Category::where('slug', $slug)
                ->orWhere('name', $name)
                ->whereNull('restaurant_id')
                ->first();

            if (!$existing) {
                try {
                    Category::create([
                        'name' => $name,
                        'slug' => $slug,
                        'restaurant_id' => null,
                        'is_default' => true,
                        'is_active' => true,
                        'sort_order' => $index + 1,
                        'description' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $createdCount++;
                    $this->command->info("Created category: $name");
                } catch (\Exception $e) {
                    $this->command->error("Failed to create category $name: " . $e->getMessage());
                }
            } else {
                $skippedCount++;
                $this->command->line("Skipped existing category: $name");
            }
        }

        $this->command->info("Seeder completed: Created $createdCount categories, Skipped $skippedCount existing categories.");
    }
}
