<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Comments;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\helpers\currentUser;

class CommentsController extends Controller {

    /**
     * @param Request $request - the request
     * @param $id - user_id for the current user
     * @param $recipe_id - recipe_id for the recipe
     * @return \Illuminate\Http\JsonResponse - details of the added comment
     * @throws \Illuminate\Validation\ValidationException
     */
    public function add(Request $request, $id, $recipe_id) {
        $currentUser = new currentUser();
        $commentor = $currentUser->getCurrentUser($request);
        if ($id == $commentor->id) {
            return response()->json('You cannot comment on your own recipe', 400);
        }

        $this->validate($request, [
            'comment' => 'required',
        ]);

        $recipe = Recipe::findOrFail($recipe_id);
        $comment = new Comments();
        $comment->Comment = $request->comment;
        $comment->recipe_id = $recipe_id;


        $comment->commentor_id = $commentor->id;
        $comment->save();
        $commentor = Comments::find($comment->id)->commentor;
        $recipe = Comments::find($comment->id)->recipe;
        $newComment = [
            'comment_id'=> $comment->id,
            'Commentor' => $commentor->username,
            'Comment' => $comment->Comment,
            'Recipe' => $recipe->name,
            'Comment date' => $comment->created_at
        ];
        return response()->json($newComment, 201);
    }

    /**
     * @param $recipe_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function view($recipe_id) {
        $allComments = [];
        $comments = Comments::where('recipe_id', $recipe_id)->get();
        if (!$comments) {
            return response()->json('Recipe has no comments', 404);
        }
        foreach ($comments as $comment) {

            $commentor = (User::find($comment->commentor_id))->username;
            $recipe = Comments::find($comment->id)->recipe;
            $commentDetail = [
                'comment_id' => $comment['id'],
                'Commentor' => $commentor,
                'Comment' => $comment['Comment'],
                'Recipe' => $recipe->name,
                'Recipe_id' => $recipe->id,
                'Comment date' => $comment['created_at']
            ];
            array_push($allComments, $commentDetail);
        }
        return response()->json($allComments, 200);
    }

    /**
     * @param Request $request
     * @param $comment_id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function edit(Request $request, $comment_id) {
        $this->validate($request, [
            'comment' => 'required',
        ]);
        $comment = Comments::findorFail($comment_id);
        $comment->Comment = $request->comment;
        $comment->save();
        return response()->json($comment, 200);
    }

    /**
     * @param $comment_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function viewSingleComment($comment_id) {
        $comment = Comments::findOrFail($comment_id);
        $commentor = (User::find($comment->commentor_id))->username;
        $recipe = Comments::find($comment->id)->recipe;
        $singleComment = [
            'comment_id' => $comment['id'],
            'Commentor' => $commentor,
            'Comment' => $comment['Comment'],
            'Recipe' => $recipe->name,
            'Recipe_id' => $recipe->id,
            'Comment date' => $comment['created_at']
        ];
        return response()->json($singleComment, 200);
    }

    /**
     * @param $comment_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($comment_id) {
        $comment = Comments::findorFail($comment_id);
        $comment->delete();
        return response()->json('Comment successfully deleted', 200);
    }

}