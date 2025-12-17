<?php

namespace Modules\Tenants\Repositories;

use App\Models\Tenant;

class TenantRepository
{
    public function createWithDomain(string $domainName): Tenant
    {
        $tenant = Tenant::create(['id' => $domainName]);
        $tenant->domains()->create(['domain' => $domainName]);

        return $tenant;
    }
}
