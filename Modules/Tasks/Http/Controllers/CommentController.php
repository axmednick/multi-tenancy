<?php
namespace Modules\Tasks\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Modules\Tasks\Entities\Task;
use Modules\Tasks\Entities\Comment;
use Modules\Tasks\Http\Requests\CommentRequest;
use Modules\Tasks\Transformers\CommentResource;
use Modules\Tasks\Repositories\CommentRepository;
use Modules\Tasks\Actions\StoreCommentAction;

class CommentController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        protected CommentRepository $repository
    ) {}

    public function index(Task $task): AnonymousResourceCollection
    {
        $comments = $this->repository->getCommentsByTask($task);

        return CommentResource::collection($comments);
    }

    public function store(CommentRequest $request, Task $task, StoreCommentAction $action): CommentResource
    {

        $this->authorize('createComment', $task);

        $comment = $action->execute($task, $request->validated());

        return CommentResource::make($comment);
    }

    public function destroy(Task $task, Comment $comment): JsonResponse
    {
        abort_if($comment->task_id !== $task->id, 404, 'Comment not found for this task');

        $this->authorize('delete', $comment);

        $this->repository->delete($comment);

        return response()->json(['message' => 'Comment deleted successfully'], 200);
    }
}
