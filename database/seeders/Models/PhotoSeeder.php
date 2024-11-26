<?php
namespace Database\Seeders\Models;

use Illuminate\Database\Seeder;
use App\Models\Profile;
use App\Models\Photo;

class PhotoSeeder extends Seeder
{
    public function run()
    {
        // Получаем все профили
        $profiles = Profile::all();

        // Для каждого профиля добавляем от 1 до 5 фотографий
        foreach ($profiles as $profile) {
            $photoCount = rand(1, 5); // Количество фотографий для профиля

            for ($i = 0; $i < $photoCount; $i++) {
                // Генерация случайного пути к файлу фотографии
                $fakePhoto = fake()->imageUrl(640, 480, 'profile', true);

                // Сохраняем информацию о фотографии в базу данных
                Photo::create([
                    'profile_id' => $profile->id,
                    'file_path' => $fakePhoto, // Ссылка на изображение (в реальном случае будет путь к сохраненному файлу)
                    'description' => fake()->sentence(), // Описание фотографии (необязательно)
                ]);
            }
        }
    }
}
