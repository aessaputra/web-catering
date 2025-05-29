<tr>
    <td>
        <table class="footer" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
            <tr>
                <td class="content-cell" align="center">
                    © {{ date('Y') }} {{ $siteSettings['site_name'] ?? config('app.name') }}.
                    {{ Illuminate\Mail\Markdown::parse(Lang::get('All rights reserved.')) }}
                </td>
            </tr>
        </table>
    </td>
</tr>
