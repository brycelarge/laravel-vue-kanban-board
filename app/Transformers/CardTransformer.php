<?php

namespace App\Transformers;

use App\Models\Card;
use League\Fractal\TransformerAbstract;

class CardTransformer extends TransformerAbstract
{
    public function transform(Card $card): array
    {
        return [
            'id' => $card->id,
            'title' => $card->title,
            'description' => $card->description,
            'deleted' => false,
        ];
    }
}
