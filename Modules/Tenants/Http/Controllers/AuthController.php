<?php
namespace Modules\Tenants\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Tenants\Actions\RegisterTenantAction;
use Modules\Users\Http\Requests\RegisterRequest;
use Modules\Users\Transformers\TenantResource;
use Exception;

class AuthController extends Controller
{

    public function register(RegisterRequest $request, RegisterTenantAction $action): JsonResponse
    {
        try {
            $tenant = $action->execute($request->validated());

            return response()->json([
                'status'  => 'success',
                'message' => 'Qeydiyyat uğurla tamamlandı.',
                'data'    => $tenant
            ], 201);

        } catch (Exception $e) {
            report($e);

            return response()->json([
                'status'  => 'error',
                'message' => 'Qeydiyyat zamanı texniki xəta baş verdi.',
                'debug'   => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
