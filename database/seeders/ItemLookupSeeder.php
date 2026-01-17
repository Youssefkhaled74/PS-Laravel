<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PieceType;
use App\Models\Gender;
use App\Models\Size;
use App\Models\Color;

class ItemLookupSeeder extends Seeder
{
    public function run()
    {
        // Piece types
        $types = [
            ['name_en' => 'Shirt', 'name_ar' => 'قميص'],
            ['name_en' => 'Pants', 'name_ar' => 'بنطال'],
            ['name_en' => 'Shoes', 'name_ar' => 'أحذية'],
        ];
        foreach ($types as $t) PieceType::firstOrCreate(['name_en' => $t['name_en']], $t);

        // Genders
        $genders = [
            ['key' => 'men','name_en' => 'Men','name_ar'=>'رجال'],
            ['key' => 'women','name_en' => 'Women','name_ar'=>'نساء'],
            ['key' => 'kids','name_en' => 'Kids','name_ar'=>'أطفال'],
            ['key' => 'unisex','name_en' => 'Unisex','name_ar'=>'للكل'],
        ];
        foreach ($genders as $g) Gender::firstOrCreate(['key' => $g['key']], $g);

        // Sizes
        $sizes = [
            ['name_en' => 'S','name_ar'=>'صغير'],
            ['name_en' => 'M','name_ar'=>'متوسط'],
            ['name_en' => 'L','name_ar'=>'كبير'],
            ['name_en' => 'XL','name_ar'=>'كبير جدًا'],
        ];
        foreach ($sizes as $s) Size::firstOrCreate(['name_en' => $s['name_en']], $s);

        // Colors
        $colors = [
            ['name_en'=>'Black','name_ar'=>'أسود','hex'=>'#000000'],
            ['name_en'=>'White','name_ar'=>'أبيض','hex'=>'#FFFFFF'],
            ['name_en'=>'Brown','name_ar'=>'بني','hex'=>'#8B5A2B'],
            ['name_en'=>'Gold','name_ar'=>'ذهبي','hex'=>'#C9A227'],
        ];
        foreach ($colors as $c) Color::firstOrCreate(['name_en' => $c['name_en']], $c);
    }
}
