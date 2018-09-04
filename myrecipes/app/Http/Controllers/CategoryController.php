<?php
namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;


class CategoryController extends Controller {

    /**
     * @param Request $request
     * @param $user_id
     * @return Category|string
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addCategory(Request $request, $user_id) {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);
        $categories = Category::select('id')->where('user_id', $user_id)->where('name', strtolower($request->name))->first();
        if ($categories) {
            return response()->json("Category already exists", 400);
        }
        $category =  new Category();
        $category->name = strtolower($request->name);
        $category->description = $request->description;
        $category->user_id = $user_id;
        $category->save();
        return $category;

    }

    /**
     * Return the user a category is associated with
     * @param $user_id
     * @return mixed
     */
    public function getUser($user_id) {
        return Category::find($user_id)->user;
    }

    /**
     * @param $user_id
     * @return \Illuminate\Http\JsonResponse Array of all categories associated with a user
     */
    public function getAllUserCategories($user_id) {
        $categories = Category::where('user_id',$user_id)->first();
        return response()->json($categories, 200);
    }

    /**
     * Return details of a single category
     * @param $id
     * @param $category_id
     * @return \Illuminate\Http\JsonResponse Object of a single category's detail
     */
    public function getSingleCategory($id, $category_id) {
        $category = Category::UserCategories($id)->where('id', $category_id)->get();
        return response()->json($category, 200);
    }

    /**
     * Edit a category
     * @param Request $request
     * @param $id
     * @param $category_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function editCategory(Request $request, $id, $category_id ) {
        $category = Category::UserCategories($id)->findorFail($category_id);
        if (!$category) {
            return response()->json("Category doesnot exist", 400);
        }
        $category->update($request->all());
        return response()->json($category, 200);
    }

    /**
     * Delete category
     * @param $id
     * @param $category_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteCategory($id, $category_id) {
        $category = Category::UserCategories($id)->findorFail($category_id);
        if (!$category) {
            return response()->json("Category doesnot exist", 400);
        }
        $category->delete();
        return response()->json($category->name.'successfully deleted', 200);
    }

    public function getCurrentUser($route_id, $user_id) {
        if ($route_id == $user_id) {
            return true;
        } else {
            return false;
        }
    }
    public function getAllCategoryRecipes($category_id) {

        return Category::find($category_id)->recipes;
    }
}