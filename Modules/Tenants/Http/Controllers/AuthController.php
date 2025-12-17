<?php

namespace Modules\Tenants\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Users\Entities\User;
use Modules\Users\Http\Requests\RegisterRequest;
use Modules\Users\Transformers\TenantResource;
use Modules\Users\Transformers\UserResource;
use Spatie\Permission\Models\Role;


class AuthController extends Controller
{

    public function register(RegisterRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        try {

            $tenant = Tenant::create([
                'id' => $validatedData['domain_name'],
            ]);

            $tenant->domains()->create([
                'domain' => $validatedData['domain_name']
            ]);

            $tenant->run(function () use ($validatedData) {

                Artisan::call('db:seed', [
                    '--class' => \Modules\Users\Database\Seeders\RolesAndPermissionsSeederTableSeeder::class,
                    '--force' => true
                ]);
                $user = User::create([
                    'name' => 'Tenant Admin',
                    'email' => $validatedData['email'],
                    'password' => Hash::make($validatedData['password']),
                ]);

                $adminRole = Role::where('name', 'admin')->where('guard_name', 'sanctum')->first();

                if ($adminRole) {
                    $user->assignRole($adminRole);
                }

                return $user;
            });

            return response()->json(['tenant' => new TenantResource($tenant)], 201);

        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Qeydiyyat zamanı verilənlər bazası xətası baş verdi.',
                'error_code' => $e->getMessage()
            ], 500);

        }
    }



}
