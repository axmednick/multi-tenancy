<!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Həftəlik Hesabat - Rəqəmsal Ekologiya</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f0f4f8; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td align="center" style="padding: 40px 0;">
            <table border="0" cellpadding="0" cellspacing="0" width="640" style="background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">

                <tr>
                    <td align="center" bgcolor="#ffffff" style="padding: 30px 0 20px 0; border-bottom: 4px solid #2ecc71;">
                        <h1 style="margin: 0; color: #1a2a3a; font-size: 28px; font-weight: 800; letter-spacing: -1px; text-transform: uppercase;">
                            <span style="color: #1072BA;">Rəqəmsal</span> <span style="color: #2ecc71;">Ekologiya</span>
                        </h1>
                    </td>
                </tr>

                <tr>
                    <td bgcolor="#fdfdfd" style="padding: 40px 40px 30px 40px; text-align: center;">
                        <h2 style="color: #2d3436; margin: 0; font-size: 22px; font-weight: 700;">Həftəlik Admin Hesabatı</h2>
                        <p style="color: #636e72; font-size: 15px; margin-top: 10px;">
                            Tenant: <span style="font-weight: bold; color: #1072BA;">{{ $tenantId }}</span> | Tarix: {{ now()->format('d.m.Y') }}
                        </p>

                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-top: 25px;">
                            <tr>
                                <td align="center">
                                    <div style="background-color: #e8f5e9; border: 1px solid #c8e6c9; border-radius: 8px; padding: 20px; display: inline-block; min-width: 200px;">
                                        <span style="display: block; font-size: 13px; color: #2e7d32; text-transform: uppercase; font-weight: bold; letter-spacing: 1px;">Cəmi Hesabat</span>
                                        <span style="display: block; font-size: 36px; font-weight: 800; color: #1b5e20;">{{ $reports->count() }}</span>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td style="padding: 0 40px;">
                        <div style="border-bottom: 2px solid #f1f2f6; padding-bottom: 10px; margin-bottom: 20px;">
                            <h3 style="color: #1a2a3a; font-size: 16px; margin: 0; font-weight: 600;">Detallı İstifadəçi Hesabatları</h3>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td style="padding: 0 40px 40px 40px;">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            @foreach ($reports as $report)
                                <tr style="background-color: #ffffff;">
                                    <td style="padding: 15px; border: 1px solid #f1f2f6; border-radius: 10px; margin-bottom: 12px; display: block;">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td width="50" valign="middle">
                                                    <div style="width: 40px; height: 40px; background-color: #f0f4f8; border-radius: 50%; text-align: center; line-height: 40px; color: #1072BA; font-weight: bold; font-size: 14px;">
                                                        ID
                                                    </div>
                                                </td>
                                                <td>
                                                    <div style="font-size: 15px; font-weight: 700; color: #2d3436;">{{ $report->user->name ?? 'Naməlum İstifadəçi' }}</div>
                                                    <div style="font-size: 12px; color: #b2bec3;">Sənəd ID: #{{ $report->id }}</div>
                                                </td>
                                                <td align="right">
                                                    <a href="{{ $report->file_url }}" style="background-color: #1072BA; color: #ffffff; text-decoration: none; padding: 10px 18px; border-radius: 6px; font-size: 12px; font-weight: bold; display: inline-block; transition: background 0.3s;">
                                                        YÜKLƏ (PDF)
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr style="height: 10px;"><td></td></tr> @endforeach
                        </table>
                    </td>
                </tr>

                <tr>
                    <td bgcolor="#f9fafb" style="padding: 30px 40px; border-top: 1px solid #edf2f7;">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tr>
                                <td style="font-size: 13px; color: #7f8c8d; line-height: 1.5;">
                                    <strong>Qeyd:</strong> Bu hesabat sistemi tərəfindən hər 7 gündən bir avtomatik formalaşdırılır. Əgər hər hansı bir texniki xəta aşkar etsəniz, lütfən IT departamentinə məlumat verin.
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td align="center" style="padding: 20px 0; background-color: #1a2a3a;">
                        <p style="margin: 0; font-size: 11px; color: #95a5a6; text-transform: uppercase; letter-spacing: 1px;">
                            &copy; {{ date('Y') }} Rəqəmsal Ekologiya Portalı
                        </p>
                    </td>
                </tr>
            </table>

            <table border="0" cellpadding="0" cellspacing="0" width="640">
                <tr>
                    <td style="padding: 20px; text-align: center; font-size: 12px; color: #95a5a6;">
                        Bu mesaj {{ auth()->user()->email ?? 'admin' }} üçün nəzərdə tutulub. <br>
                        Bakı, Azərbaycan
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
