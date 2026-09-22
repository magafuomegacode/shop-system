<div style="background: #f8fafc; border-radius: 0 0 16px 16px; padding: 24px 32px; text-align: center; border-top: 1px solid #e2e8f0;">
    @if($systemPhone || $systemEmail)
        <p style="margin: 0 0 8px; color: #64748b; font-size: 13px;">
            @if($systemPhone) 📞 {{ $systemPhone }} @endif
            @if($systemPhone && $systemEmail) · @endif
            @if($systemEmail) ✉️ {{ $systemEmail }} @endif
        </p>
    @endif
    <p style="margin: 0; color: #94a3b8; font-size: 12px;">
        © {{ date('Y') }} {{ $systemName }} · All rights reserved
    </p>
</div>