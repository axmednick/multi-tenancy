<?php

namespace Modules\Tenants\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;

class IdentifyTenant
{
    public function handle($request, Closure $next)
    {
        $host = $request->getHost(); // Məsələn: rustamov.etsn.carbuy.az
        $centralDomains = config('tenancy.central_domains', []);

        if (in_array($host, $centralDomains)) {
            return $next($request);
        }

        $subdomain = $host;
        foreach ($centralDomains as $central) {
            if (str_ends_with($host, '.' . $central)) {
                // '.etsn.carbuy.az' hissəsini silirik
                $subdomain = str_replace('.' . $central, '', $host);
                break;
            }
        }

        $tenant = Tenant::whereHas('domains', function ($q) use ($subdomain) {
            $q->where('domain', $subdomain);
        })->first();

        if (!$tenant) {
            abort(404, 'Tenant tapılmadı (Subdomain: ' . $subdomain . ')');
        }

        tenancy()->initialize($tenant);

        return $next($request);
    }
}
