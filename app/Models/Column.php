<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Column
 *
 * @property int $id
 * @property string $title
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Card[] $cards
 * @method static Builder|Column newModelQuery()
 * @method static Builder|Column newQuery()
 * @method static Builder|Column query()
 */
class Column extends BaseModel
{
    protected $casts = [
        'id' => 'int',
    ];

    public function cards(): BelongsToMany
    {
        return $this->belongsToMany(Card::class, 'card_associations');
    }
}
