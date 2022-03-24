<?php

namespace Modules\Food\Services\Recipe;

use App\Dictionary;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Modules\Food\Entities\FactModel;
use Modules\Food\Entities\FoodCookingModel;
use Modules\Food\Entities\FoodNoteModel;
use Modules\Food\Entities\FoodPictureModel;
use Modules\Food\Entities\FoodPreparationStepModel;
use Modules\Food\Entities\MeasurementModel;
use Modules\Food\Entities\RecipeIngredientModel;
use Modules\Food\Entities\RecipeModel;
use Throwable;

class RecipeCreateService extends RecipeService
{
    private RecipeModel $recipe_row;

    /**
     * @param array $payload
     * @throws Throwable
     */
    public function create(array $payload)
    {
        $this->payload = $payload;
        $this->recordMeta()
            ->recordPhoto()
            ->recordMeasurement()
            ->recordPreparationSteps()
            ->recordCooking()
            ->recordNotes()
            ->recordIngredients();
    }

    private function recordMeta(): RecipeCreateService
    {
        $this->recipe_row = RecipeModel::forceCreate([
            'food_id' => Dictionary::RECIPE . '_' . Str::uuid(),
            'name' => $this->payload['name'],
            'description' => Arr::exists($this->payload, 'description') ?
                $this->payload['description'] : null
        ]);
        return $this;
    }

    /**
     * @return RecipeCreateService
     * @throws Throwable
     */
    private function recordPhoto(): RecipeCreateService
    {
        $data = base64_decode($this->payload['photo']['data']);
        $mime = request()->get('mime');
        $full_path = Str::random(10) . '.' . request()->get('mime');
        $path = $this->recipe_row->food_id . '/' . $full_path;
        $thumbnail = Image::make($data)->resize(64, 64)->encode($mime);
        $thumbnail_path = $this->recipe_row->food_id . '/' . 'thumbnail' . '.' . $mime;
        Storage::disk('recipes')->put($path, $data);
        Storage::disk('recipes')->put($thumbnail_path, $thumbnail);
        FoodPictureModel::forceCreate([
            'food_id' => $this->recipe_row->food_id,
            'full_path' => $full_path,
            'thumbnail_path' => 'thumbnail' . '.' . $mime
        ]);
        return $this;
    }

    private function recordMeasurement(): RecipeCreateService
    {
        $measurement = MeasurementModel::forceCreate([
            'food_id' => $this->recipe_row->food_id,
            'measure_type' => $this->payload['measurement']['quantity']['unit'],
            'quantity' => $this->payload['measurement']['quantity']['amount'],
            'weight' => Arr::exists($this->payload['measurement'], 'weight') ?
                $this->payload['measurement']['weight']['amount'] : null,
            'weight_unit' =>  Arr::exists($this->payload['measurement'], 'weight') ?
                $this->payload['measurement']['weight']['unit'] : null,
        ]);
        FactModel::forceCreate([
            'measurement_id' => $measurement->id,
            'calories' => $this->payload['measurement']['facts']['calories']['amount'],
            'calories_unit' => $this->payload['measurement']['facts']['calories']['unit'],
            'carbs' => $this->payload['measurement']['facts']['carbs']['amount'],
            'carbs_unit' => $this->payload['measurement']['facts']['carbs']['unit'],
            'protein' => $this->payload['measurement']['facts']['protein']['amount'],
            'protein_unit' => $this->payload['measurement']['facts']['protein']['unit'],
            'fat' => $this->payload['measurement']['facts']['fat']['amount'],
            'fat_unit' => $this->payload['measurement']['facts']['fat']['unit'],
        ]);
        return $this;
    }

    private function recordPreparationSteps(): RecipeCreateService
    {
        foreach ($this->payload['preparationSteps'] as $preparationStep) {
            FoodPreparationStepModel::forceCreate([
                'food_id' => $this->recipe_row->food_id,
                'order' => $preparationStep['order'],
                'step' => $preparationStep['content']
            ]);
        }
        return $this;
    }

    private function recordCooking(): RecipeCreateService
    {
        FoodCookingModel::forceCreate([
            'food_id' => $this->recipe_row->food_id,
            'method' => Arr::exists($this->payload['cooking'], 'method') ?
                $this->payload['cooking']['method'] : null,
            'time' => $this->payload['cooking']['time']['amount'],
            'time_unit' => $this->payload['cooking']['time']['unit']
        ]);
        return $this;
    }

    private function recordNotes(): RecipeCreateService
    {
        $notes = Arr::exists($this->payload, 'notes') ? $this->payload['notes'] : [];
        foreach ($notes as $note) {
            FoodNoteModel::forceCreate([
                'food_id' => $this->recipe_row->food_id,
                'note' => $note
            ]);
        }
        return $this;
    }

    private function recordIngredients()
    {
        foreach ($this->payload['ingredients'] as $ingredient) {
            RecipeIngredientModel::forceCreate([
                'recipe_id' => $this->recipe_row->food_id,
                'ingredient_id' => $ingredient['id'],
                'quantity' => Arr::has($ingredient, 'quantity') ? $ingredient['quantity']['amount'] : null,
                'quantity_unit' => Arr::has($ingredient, 'quantity') ? $ingredient['quantity']['unit'] : null,
            ]);
        }
    }


}
