<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\File;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FileFactory extends Factory
{
    protected $model = File::class;

    public function definition()
    {
        $user = User::all()->random();
        $documents = Document::all()->random();
        $extension = fake()->fileExtension();
        return [
            'name_file' => fake()->word() . '.' . $extension,
            'extension_file' => $extension,
            'document_id' => $documents->id,
            'created_user_id' => $user->id,
            'updated_user_id' => $user->id,
            'created_date' => fake()->dateTime(),
            'updated_date' => fake()->dateTime()
        ];
    }
}
