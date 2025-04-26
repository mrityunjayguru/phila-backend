<?php

use Illuminate\Database\Seeder;
use App\Models\Language;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $languages = [
            ['language' => 'English', 'tag' => 'US'],
            ['language' => 'German', 'tag' => 'Deutsch'],
            ['language' => 'Spanish', 'tag' => 'Español'],
            ['language' => 'French', 'tag' => 'Francesa'],
            ['language' => 'Japanese', 'tag' => '日本語'],
            ['language' => 'Korean', 'tag' => '韓国語'],
            ['language' => 'Italian', 'tag' => 'Italian'],
            ['language' => 'Hindi', 'tag' => 'हिंदी'],
            ['language' => 'Russian', 'tag' => 'Русский'],
            ['language' => 'Chinese', 'tag' => '中国人'],
            ['language' => 'Portuguese', 'tag' => 'Português'],
            ['language' => 'Vietnamese', 'tag' => 'Tiếng Việt'],
        ];

        foreach ($languages as $language) {
            Language::create($language);
        }
    }
}