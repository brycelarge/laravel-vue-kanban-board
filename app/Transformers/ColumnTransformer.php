<?php

namespace App\Transformers;

use App\Models\Column;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;

class ColumnTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'cards',
    ];

    public function transform(Column $column): array
    {
        return [
            'id' => $column->id,
            'title' => $column->title,
            'deleted' => false,
        ];
    }

    public function includeCards(Column $column): Collection
    {
        return $this->collection($column->cards, new CardTransformer());
    }
}
