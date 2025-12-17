<?php
namespace Modules\Tenants\Actions;

use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Modules\Tenants\Repositories\TenantRepository;
use Modules\Users\Repositories\UserRepository;
use Spatie\Permission\Models\Role;
use Exception;

class RegisterTenantAction
{
    public function __construct(
        protected TenantRepository $tenantRepository,
        protected UserRepository $userRepository
    ) {}

    /**
     * @param array $data
     * @return Tenant
     * @throws Exception
     */
    public function execute(array $data): Tenant
    {
        $tenant = $this->tenantRepository->createWithDomain($data['domain_name']);

        try {
            $tenant->run(function () use ($data) {

                DB::transaction(function () use ($data) {

                    Artisan::call('db:seed', [
                        '--class' => \Modules\Users\Database\Seeders\RolesAndPermissionsSeederTableSeeder::class,
                        '--force' => true
                    ]);

                    $user = $this->userRepository->create([
                        'name'     => 'Tenant Admin',
                        'email'    => $data['email'],
                        'password' => Hash::make($data['password']),
                    ]);

                    $adminRole = Role::where('name', 'admin')->where('guard_name', 'sanctum')->first();
                    if ($adminRole) {
                        $user->assignRole($adminRole);
                    }
                });
            });

            return $tenant;

        } catch (Exception $e) {

            $tenant->delete();

            throw $e;
        }
    }
}
