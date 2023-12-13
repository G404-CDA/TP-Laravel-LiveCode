<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class RecipeController extends BaseController
{

    private RepositoryInterface $recipeRepository;

    public function __construct(RepositoryInterface $repository)
    {
        $this->recipeRepository = $repository;
    }
   /**
     * @OA\Get(
     *     path="/api/recipes",
     *     summary="Liste des recettes",
     *     @OA\Response(response=200, description="Liste des recettes"),
     * )
     */
    public function all()
    {
        $recipes = $this->recipeRepository->getAll();
        return $recipes;
    }


    /**
     * @OA\Get(
     *     path="/api/recipe/{id}",
     *     summary="Une recette",
     *     @OA\Parameter(
     *       name="id",
     *       in="path",
     *       description="ID de la recette",
     *       required=true,
     *       @OA\Schema(type="string"),
     *     ),
     *     @OA\Response(response=200, description="Une recette"),
     * )
     */
    public function getById(string $id)
    {
        $recipe = $this->recipeRepository->getById($id);
        return $recipe;
    }

    /**
     * @OA\Post(
     *     path="/api/recipe/add",
     *     summary="Créer une recette",

     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                      property="ingredients",
     *                      type="string",
     *                      example="Poulet, curry, riz"
     *                 ),
     *                 @OA\Property(
     *                     property="preparationTime",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="cookingTime",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="serves",
     *                     type="integer"
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="Une recette"),
     * )
     */
    public function store(Request $request)
    {
        $recipeData = $request->all();
        $recipe = $this->recipeRepository->create($recipeData);
        return $recipe;
    }

    /**
     * @OA\Put(
     *     path="/api/recipe/modify/{id}",
     *     summary="Modifier une recette",
     *     @OA\Parameter(
     *       name="id",
     *       in="path",
     *       description="ID de la recette",
     *       required=true,
     *       @OA\Schema(type="string"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                      property="ingredients",
     *                      type="string",
     *                      example="Poulet, curry, riz"
     *                 ),
     *                 @OA\Property(
     *                     property="preparationTime",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="cookingTime",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="serves",
     *                     type="integer"
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="Une recette"),
     * )
     */
    public function update(string $id, Request $request)
    {
        $recipeData = $request->all();
        $recipe = $this->recipeRepository->update($id, $recipeData);
        return $recipe;
    }


        /**
     * @OA\Delete(
     *     path="/api/recipe/delete/{id}",
     *     summary="Supprimer une recette",
     *     @OA\Parameter(
     *       name="id",
     *       in="path",
     *       description="ID de la recette",
     *       required=true,
     *       @OA\Schema(type="string"),
     *     ),
     *     @OA\Response(response=200, description="Une recette"),
     * )
     */
    public function delete(string $id)
    {
        return $this->recipeRepository->delete($id);
    }
}
