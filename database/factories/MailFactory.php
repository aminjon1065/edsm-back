<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\Mail;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MailFactory extends Factory
{
    protected $model = Mail::class;

    public function definition(): array
    {
        $user = User::all()->random();
        $documentId = Document::all()->random()->id;
        return [
            'to' => User::all()->random()->id,
            'from' => $user->id,
            'from_user_name' => $user->full_name,
            'document_id' => $documentId,
        ];
    }
}
