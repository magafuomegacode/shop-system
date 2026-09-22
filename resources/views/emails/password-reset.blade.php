<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
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
                                🔑
                            </div>
                            <h1 style="margin: 0; font-size: 24px; font-weight: 700; color: #ffffff; letter-spacing: -0.5px;">
                                {{ $systemName }}
                            </h1>
                            <p style="margin: 8px 0 0; color: #94a3b8; font-size: 14px;">
                                Password Reset
                            </p>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="background: #ffffff; padding: 40px 32px;">

                            <p style="margin: 0 0 8px; color: #0f172a; font-size: 18px; font-weight: 600;">
                                Habari, {{ $user->full_name }} 👋
                            </p>

                            <p style="margin: 0 0 24px; color: #64748b; font-size: 15px; line-height: 1.6;">
                                Password yako kwenye <strong style="color: #0f172a;">{{ $systemName }}</strong> imebadilishwa. Hapa ni password yako mpya:
                            </p>

                            {{-- Password Box --}}
                            <table role="presentation" style="width: 100%; border-collapse: collapse; background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 12px; margin-bottom: 24px;">
                                <tr>
                                    <td style="padding: 24px; text-align: center;">
                                        <p style="margin: 0 0 8px; color: #94a3b8; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700;">
                                            New Password
                                        </p>
                                        <p style="margin: 0; color: #0f172a; font-size: 28px; font-family: 'Courier New', monospace; font-weight: 700; letter-spacing: 4px;">
                                            {{ $newPassword }}
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            {{-- Account info --}}
                            <table role="presentation" style="width: 100%; border-collapse: collapse; margin-bottom: 24px;">
                                <tr>
                                    <td style="padding: 12px 0; border-bottom: 1px solid #f1f5f9;">
                                        <span style="color: #94a3b8; font-size: 13px;">Username:</span>
                                        <span style="float: right; color: #0f172a; font-size: 13px; font-weight: 600; font-family: 'Courier New', monospace;">
                                            {{ $user->username }}
                                        </span>
                                    </td>
                                </tr>
                                @if($user->email)
                                    <tr>
                                        <td style="padding: 12px 0; border-bottom: 1px solid #f1f5f9;">
                                            <span style="color: #94a3b8; font-size: 13px;">Email:</span>
                                            <span style="float: right; color: #0f172a; font-size: 13px; font-weight: 600;">
                                                {{ $user->email }}
                                            </span>
                                        </td>
                                    </tr>
                                @endif
                            </table>

                            {{-- Warning --}}
                            <table role="presentation" style="width: 100%; border-collapse: collapse; background: #fee2e2; border-left: 4px solid #ef4444; border-radius: 8px; margin-bottom: 32px;">
                                <tr>
                                    <td style="padding: 16px;">
                                        <p style="margin: 0; color: #991b1b; font-size: 14px; line-height: 1.5;">
                                            🔒 <strong>Muhimu:</strong> Badilisha password hii mara baada ya kuingia. Kama hukutarajia mabadiliko haya, wasiliana na admin wa mfumo <strong>mara moja</strong>.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            {{-- CTA Button --}}
                            <table role="presentation" style="width: 100%; border-collapse: collapse;">
                                <tr>
                                    <td align="center" style="padding-bottom: 24px;">
                                        <a href="{{ route('login') }}"
                                           style="display: inline-block; background: linear-gradient(135deg, #eab308 0%, #84cc16 100%); color: #ffffff; text-decoration: none; padding: 16px 40px; border-radius: 12px; font-weight: 700; font-size: 16px; letter-spacing: 0.3px;">
                                            🔓 Ingia kwenye Mfumo
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            {{-- Alternative Link --}}
                            <p style="margin: 0; color: #94a3b8; font-size: 12px; line-height: 1.6; text-align: center;">
                                Kama kitufe hakitafanya kazi, copy link hii kwenye browser:<br>
                                <a href="{{ route('login') }}" style="color: #3b82f6; text-decoration: none; word-break: break-all;">
                                    {{ route('login') }}
                                </a>
                            </p>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background: #f8fafc; border-radius: 0 0 16px 16px; padding: 24px 32px; text-align: center; border-top: 1px solid #e2e8f0;">
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