<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Recipe;
use Illuminate\Http\Request;

class RecipesController extends Controller {

    /**
     * Add a recipe
     * @param Request $request
     * @param $category_id
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addRecipe (Request $request, $category_id, $id) {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);
        $recipe = Recipe::CategoryRecipes($category_id)->where('name', strtolower($request->name))->first();
        if ($recipe) {
            return response()->json('Name already taken. Please pick another one', 400);
        }
        $new_recipe = new Recipe();
        $new_recipe->name = strtolower($request->name);
        $new_recipe->description = $request->description;
        $new_recipe->category_id = $category_id;
        $new_recipe->save();
        return response()->json($new_recipe, 201);
    }

    /**
     * Edit a recipe
     * @param Request $request
     * @param $category_id - id of category to which recipe belongs
     * @param $recipe_id - id of recipe to be edited
     * @return \Illuminate\Http\JsonResponse - details of the edited recipe
     * @throws \Illuminate\Validation\ValidationException
     */
    public function edit(Request$request, $category_id, $recipe_id) {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);

        $recipe = Recipe::CategoryRecipes($category_id)->findorFail($recipe_id);
        if (!$recipe) {
            return response()->json('Recipe doesnot exist', 400);
        }
        $recipe->update($request->all());
        return response()->json($recipe, 200);
    }

    /**
     * Delete a single recipe
     * @param $category_id
     * @param $recipe_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($category_id, $recipe_id) {
        $recipe = Recipe::CategoryRecipes($category_id)->findorFail($recipe_id);
        $recipe->delete();
        return response()->json('Recipe deleted successfully', 200);
    }

    /**
     * Get the details of a single recipe
     * @param $category_id
     * @param $recipe_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSingleRecipe($category_id, $recipe_id) {
        $recipe = Recipe::findorFail($recipe_id);
        return response()->json($recipe, 200);
    }
}