<?php


namespace App\Repositories;
use App\Models\Recipe;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class RecipeRepository implements RepositoryInterface
{

    public function getAll(): Collection
    {
        return Recipe::all();
    }

    public function getById(string $id): Recipe
    {
        return Recipe::findOrFail($id);
    }

    public function create(array $data): Recipe
    {
        return Recipe::create($data);
    }

    public function update(string $id, array $data): Recipe
    {
        $recipe = Recipe::findOrFail($id);
        $recipe->update($data);
        return $recipe;
    }

    public function delete(string $id): void
    {
        $recipe = Recipe::find($id);
        $recipe->delete();
    }
}
