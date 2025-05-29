@props(['url'])
<tr>
    <td class="header">
        <a href="{{ $url }}">
            🍰 {{ app(\App\Models\Setting::class)->where('key', 'site_name')->first()?->value ?? $slot }}
        </a>
    </td>
</tr>
