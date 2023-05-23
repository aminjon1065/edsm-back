<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\Mail;
use App\Models\OpenedMail;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OpenedMailFactory extends Factory
{
    protected $model = OpenedMail::class;

    public function definition(): array
    {
        $arrOpened = [1, 2];
        return [
            'mail_id' => Mail::all()->random()->id,
            'user_id' => User::all()->random()->id,
            'document_id' => Document::all()->random()->id,
            'opened' => array_rand($arrOpened)
        ];
    }
}
