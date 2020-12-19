<?php

namespace App\Http\Controllers;

use App\Models\BaseModel;
use App\Models\Card;
use App\Models\Column;
use App\Transformers\ArraySerializer;
use App\Transformers\ColumnTransformer;

class ApiController extends Controller
{
    public function index()
    {
        return fractal()
            ->collection(Column::with('cards')->get())
            ->parseIncludes(['cards'])
            ->serializeWith(new ArraySerializer()) // remove the data key from response
            ->transformWith(new ColumnTransformer())
            ->toArray();
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function persist()
    {
        $this->validate(request(), [
            'columns' => 'required|array',
            'columns.cards.*' => 'required|array',
            'columns.title.*' => 'required|string|min:3|max:100',
            'columns.cards.*.title.*' => 'required|string|min:3|max:100',
        ]);

        $columns = collect();

        // There must be a more efficient way of doing this
        // but my 1 month old baby is taking up most of my time so lets do the fastest route for now
        foreach (request('columns') as $columnData) {
            $isColumnDeleted = (bool) $columnData['deleted'] ?? false;
            $column = $this->getColumn($columnData['title'], $columnData['id'] ?? null);

            $cardIds = [];
            $columnCards = collect();
            foreach ($columnData['cards'] as $cardData) {
                if ($cardId = $cardData['id'] ?? null) {
                    $card = Card::findOrFail($cardId);
                    $isCardDeleted = (bool) $cardData['deleted'];
                    if ($isColumnDeleted || $isCardDeleted) {
                        $card->forceDelete();
                        continue;
                    }

                    $card->update(['title' => $cardData['title'], 'description' => $cardData['description']]);
                } elseif (!$isColumnDeleted) { // dont create the card if the column is deleted
                    $card = Card::create(['title' => $cardData['title'], 'description' => $cardData['description']]);
                }

                if (isset($card)) {
                    $cardIds[] = $card->id;
                    $columnCards->push($card);
                }
            }

            if ($isColumnDeleted) {
                $column->forceDelete();
                continue;
            }

            if (count($cardIds)) {
                $column->cards()->attach($cardIds);
            }

            // so we dont have to re-fetch them when the transformer gets hold of each column
            if ($columnCards->isNotEmpty()) {
                $column->setRelation('cards', $columnCards);
            }

            $columns->push($column);
        }

        return fractal()
            ->collection($columns)
            ->parseIncludes(['cards'])
            ->serializeWith(new ArraySerializer()) // remove the data key from response
            ->transformWith(new ColumnTransformer())
            ->toArray();
    }

    public function dumpDb()
    {
        // I can also do this in strait bash script and email someone once the dump is complete
        // Most of our automation between our 4 servers is all done with bash scripting and interacting between dockers

        try {
            $dump = BaseModel::dbDump();

            return response()->download($dump->getRealPath(), $dump->getFilename())->deleteFileAfterSend(true);
        } catch (\Throwable $e) {
            return response('Unknown error', 500);
        }
    }

    private function getColumn(string $title, $id = null): Column
    {
        if ($id) {
            $column = Column::findOrFail($id);
            $column->cards()->detach(); // remove all card associations
        } else {
            $column = Column::create(['title' => $title]);
        }

        return $column;
    }
}
