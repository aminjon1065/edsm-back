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
        $documentId = Document::all()->random()->id;
        return [
            'to' => User::all()->random()->id,
            'from' => User::all()->random()->id,
            'document_id' => $documentId,
        ];
    }
}
