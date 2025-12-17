<?php

namespace Modules\Tasks\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Tasks\Entities\Task;
use Modules\Tasks\Entities\Comment;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Modules\Tasks\Http\Requests\CommentRequest;
use Modules\Tasks\Transformers\CommentResource;

class CommentController extends Controller
{
    use AuthorizesRequests;

    public function index(Task $task): ResourceCollection
    {
        $comments = $task->comments()->latest()->get();
        return CommentResource::collection($comments);
    }


    public function store(CommentRequest $request, Task $task): JsonResource
    {
        $validated = $request->validated();

        $comment = $task->comments()->create([
            'user_id' => auth('sanctum')->id(),
            'comment' => $validated['comment'],
            'task_id' => $task->id,
        ]);

        return  CommentResource::make($comment);
    }

    public function destroy(Task $task, Comment $comment): JsonResponse
    {
        if ($comment->task_id !== $task->id) {
            return response()->json(['message' => 'Comment not found for this task'], 404);
        }


        $this->authorize('delete', $comment);

        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully']);
    }
}
