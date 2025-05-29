@props(['url'])
<tr>
    <td class="header">
        <a href="{{ $url }}">
            @if (isset($siteSettings['site_logo']) &&
                    $siteSettings['site_logo'] &&
                    file_exists(public_path('storage/' . $siteSettings['site_logo'])))
                <img src="{{ asset('storage/' . $siteSettings['site_logo']) }}" class="logo"
                    alt="{{ $siteSettings['site_name'] ?? '' }}" style="max-height: 50px;" />
            @else
                {{ $siteSettings['site_name'] ?? 'Catering Lezat' }}
            @endif
        </a>
    </td>
</tr>
