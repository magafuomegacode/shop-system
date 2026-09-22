<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Credentials</title>
</head>
<body style="margin: 0; padding: 0; background: #f1f5f9; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;">
    <table role="presentation" style="width: 100%; border-collapse: collapse; background: #f1f5f9;">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                <table role="presentation" style="max-width: 600px; width: 100%; border-collapse: collapse;">

                    {{-- Header --}}
                    <tr>
                        <td style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border-radius: 16px 16px 0 0; padding: 40px 32px; text-align: center;">
                            <div style="display: inline-block; width: 64px; height: 64px; background: linear-gradient(135deg, #eab308 0%, #84cc16 100%); border-radius: 16px; line-height: 64px; text-align: center; font-size: 32px; margin-bottom: 16px;">
                                🛒
                            </div>
                            <h1 style="margin: 0; font-size: 24px; font-weight: 700; color: #ffffff; letter-spacing: -0.5px;">
                                {{ $systemName }}
                            </h1>
                            <p style="margin: 8px 0 0; color: #94a3b8; font-size: 14px;">
                                Your account is ready
                            </p>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="background: #ffffff; padding: 40px 32px;">

                            <p style="margin: 0 0 8px; color: #0f172a; font-size: 18px; font-weight: 600;">
                                Karibu, {{ $user->full_name }}! 👋
                            </p>

                            <p style="margin: 0 0 24px; color: #64748b; font-size: 15px; line-height: 1.6;">
                                Akaunti yako imeundwa kwenye <strong style="color: #0f172a;">{{ $systemName }}</strong>. Hapa ni credentials zako za kuingia kwenye mfumo:
                            </p>

                            {{-- Credentials Box --}}
                            <table role="presentation" style="width: 100%; border-collapse: collapse; background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 12px; margin-bottom: 24px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <table role="presentation" style="width: 100%; border-collapse: collapse;">
                                            <tr>
                                                <td style="padding-bottom: 16px; border-bottom: 1px solid #e2e8f0;">
                                                    <p style="margin: 0 0 4px; color: #94a3b8; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700;">
                                                        Username
                                                    </p>
                                                    <p style="margin: 0; color: #0f172a; font-size: 20px; font-family: 'Courier New', monospace; font-weight: 700; letter-spacing: 1px;">
                                                        {{ $user->username }}
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-top: 16px;">
                                                    <p style="margin: 0 0 4px; color: #94a3b8; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700;">
                                                        Password
                                                    </p>
                                                    <p style="margin: 0; color: #0f172a; font-size: 20px; font-family: 'Courier New', monospace; font-weight: 700; letter-spacing: 1px;">
                                                        {{ $password }}
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            {{-- Role --}}
                            <p style="margin: 0 0 24px; color: #64748b; font-size: 14px;">
                                <strong style="color: #0f172a;">Role:</strong> 
                                <span style="display: inline-block; background: #ecfccb; color: #3f6212; padding: 4px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </p>

                            {{-- Warning --}}
                            <table role="presentation" style="width: 100%; border-collapse: collapse; background: #fef3c7; border-left: 4px solid #f59e0b; border-radius: 8px; margin-bottom: 32px;">
                                <tr>
                                    <td style="padding: 16px;">
                                        <p style="margin: 0; color: #92400e; font-size: 14px; line-height: 1.5;">
                                            ⚠️ <strong>Muhimu:</strong> Badilisha password yako mara baada ya kuingia kwa usalama wa akaunti yako.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            {{-- CTA Button --}}
                            <table role="presentation" style="width: 100%; border-collapse: collapse;">
                                <tr>
                                    <td align="center" style="padding-bottom: 24px;">
                                        <a href="{{ $loginUrl }}"
                                           style="display: inline-block; background: linear-gradient(135deg, #eab308 0%, #84cc16 100%); color: #ffffff; text-decoration: none; padding: 16px 40px; border-radius: 12px; font-weight: 700; font-size: 16px; letter-spacing: 0.3px;">
                                            🔓 Ingia kwenye Mfumo
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            {{-- Alternative Link --}}
                            <p style="margin: 0; color: #94a3b8; font-size: 12px; line-height: 1.6; text-align: center;">
                                Kama kitufe hakitafanya kazi, copy link hii kwenye browser:<br>
                                <a href="{{ $loginUrl }}" style="color: #3b82f6; text-decoration: none; word-break: break-all;">
                                    {{ $loginUrl }}
                                </a>
                            </p>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background: #f8fafc; border-radius: 0 0 16px 16px; padding: 24px 32px; text-align: center; border-top: 1px solid #e2e8f0;">
                            @if($systemPhone || $systemEmail)
                                <p style="margin: 0 0 8px; color: #64748b; font-size: 13px;">
                                    <strong style="color: #0f172a;">Wasiliana nasi:</strong>
                                    @if($systemPhone) 📞 {{ $systemPhone }} @endif
                                    @if($systemPhone && $systemEmail) · @endif
                                    @if($systemEmail) ✉️ {{ $systemEmail }} @endif
                                </p>
                            @endif
                            <p style="margin: 0; color: #94a3b8; font-size: 12px;">
                                © {{ date('Y') }} {{ $systemName }} · All rights reserved
                            </p>
                            <p style="margin: 8px 0 0; color: #cbd5e1; font-size: 11px;">
                                Email hii ilitumwa kwa {{ $user->email }}. Kama hukutarajia email hii, tafadhali puuza.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
