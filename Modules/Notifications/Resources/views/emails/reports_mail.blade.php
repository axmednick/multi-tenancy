<!DOCTYPE html>
<html>
<head>
    <title>Tenant Reportları: {{ $tenantId }}</title>
</head>
<body>
<h1>Həftəlik Admin Hesabatı - {{ $tenantId }}</h1>
<p>Cari həftə üçün {{ $reports->count() }} istifadəçi hesabatı yaradılmışdır. Aşağıdakı linklərdən PDF sənədlərini
    yükləyə bilərsiniz.</p>
<hr>

<ul>
    @foreach ($reports as $report)
        <li>
            <strong>İstifadəçi:</strong> {{ $report->user->name ?? 'N/A' }}
            (Report ID: {{ $report->id }}) –
            <a href="{{ $report->file_url }}"
               style="color:#1072BA;text-decoration:none;font-weight:bold;">
                [PDF Yüklə]
            </a>
        </li>
    @endforeach

</ul>

<hr>
<p>Bu avtomatik yaradılmış bir e-poçtdur.</p>
</body>
</html>
