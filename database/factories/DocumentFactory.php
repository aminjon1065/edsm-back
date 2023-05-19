<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\Mail;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentFactory extends Factory
{
    protected $model = Document::class;

    public function definition(): array
    {
        $user = User::all()->random();
        $importanceArr = ['normal', 'important'];
        $statusArr = ['pending', 'watched', 'late'];
        $mails = Mail::all()->random();
        return [
            'title_document' => fake()->word(),
            'description_document' => fake()->text(),
            'content' => fake()->text(),
            'region' => $user->region,
            'importance' => array_rand($importanceArr),
            'status' => array_rand($statusArr),
            'created_user_id' => $user->id,
            'updated_user_id' => $user->id,
            'mail_id' => $mails->id,
            'created_date' => fake()->dateTime(),
            'updated_date' => fake()->dateTime(),
        ];
    }
}
