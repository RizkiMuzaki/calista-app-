<?php

namespace Database\Seeders;

use App\Models\Anak;
use App\Models\CharacterItem;
use App\Models\ChildItem;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CharacterItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'name' => 'Nusa Safari Klasik',
                'description' => 'Outfit safari pertama Nusa untuk mulai petualangan di kebun binatang.',
                'image_url' => 'assets/images/shop/nusa_safari_klasik.png',
                'unlock_type' => 'free',
                'reward_condition' => null,
                'sort_order' => 1,
            ],
            [
                'name' => 'Nusa Croco Ranger',
                'description' => 'Hadiah untuk anak yang mulai berani menyelesaikan tantangan belajar.',
                'image_url' => 'assets/images/shop/nusa_croco_ranger.png',
                'unlock_type' => 'reward',
                'reward_condition' => '5',
                'sort_order' => 2,
            ],
            [
                'name' => 'Nusa Rimba Explorer',
                'description' => 'Hadiah spesial untuk penjelajah kecil yang rajin menyelesaikan level.',
                'image_url' => 'assets/images/shop/nusa_rimba_explorer.png',
                'unlock_type' => 'reward',
                'reward_condition' => '10',
                'sort_order' => 3,
            ],
            [
                'name' => 'Nusa Elephant Ranger',
                'description' => 'Welcome gift spesial untuk member pertama Calista Plus.',
                'image_url' => 'assets/images/shop/nusa_elephant_ranger.png',
                'unlock_type' => 'premium',
                'reward_condition' => null,
                'sort_order' => 4,
            ],
            [
                'name' => 'Nusa Panda Scout',
                'description' => 'Kostum premium lucu untuk petualangan belajar yang lebih seru.',
                'image_url' => 'assets/images/shop/nusa_panda_scout.png',
                'unlock_type' => 'premium',
                'reward_condition' => null,
                'sort_order' => 5,
            ],
            [
                'name' => 'Nusa Tiger Keeper',
                'description' => 'Kostum premium penuh energi untuk menjaga semangat belajar.',
                'image_url' => 'assets/images/shop/nusa_tiger_keeper.png',
                'unlock_type' => 'premium',
                'reward_condition' => null,
                'sort_order' => 6,
            ],
            [
                'name' => 'Nusa Koala Explorer',
                'description' => 'Kostum premium yang hangat dan ramah untuk si kecil.',
                'image_url' => 'assets/images/shop/nusa_koala_explorer.png',
                'unlock_type' => 'premium',
                'reward_condition' => null,
                'sort_order' => 7,
            ],
            [
                'name' => 'Nusa Zoo Guide',
                'description' => 'Kostum pemandu kebun binatang untuk member Calista Plus.',
                'image_url' => 'assets/images/shop/nusa_zoo_guide.png',
                'unlock_type' => 'premium',
                'reward_condition' => null,
                'sort_order' => 8,
            ],
        ];

        foreach ($items as $itemData) {
            CharacterItem::updateOrCreate(
                ['name' => $itemData['name']],
                $itemData
            );
        }

        CharacterItem::whereNotIn('name', array_column($items, 'name'))
            ->whereIn('unlock_type', ['free', 'reward', 'premium'])
            ->update(['is_active' => false]);

        ChildItem::whereHas('characterItem', function ($query) {
            $query->where('is_active', false);
        })->update(['is_equipped' => false]);

        $freeItems = CharacterItem::free()->active()->get();
        $children = Anak::all();

        foreach ($children as $child) {
            foreach ($freeItems as $index => $freeItem) {
                ChildItem::updateOrCreate(
                    [
                        'anak_id' => $child->id,
                        'character_item_id' => $freeItem->id,
                    ],
                    [
                        'is_equipped' => $index === 0,
                        'unlocked_at' => Carbon::now(),
                    ]
                );
            }

            $hasActiveEquippedItem = ChildItem::where('anak_id', $child->id)
                ->where('is_equipped', true)
                ->whereHas('characterItem', function ($query) {
                    $query->where('is_active', true);
                })
                ->exists();

            if (!$hasActiveEquippedItem && $freeItems->isNotEmpty()) {
                ChildItem::where('anak_id', $child->id)
                    ->where('character_item_id', $freeItems->first()->id)
                    ->update(['is_equipped' => true]);
            }
        }

        $this->command->info('8 item shop Nusa berhasil dibuat/diupdate.');
        $this->command->info("Item gratis diberikan ke {$children->count()} anak.");
    }
}
