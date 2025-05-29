@props(['url'])
<tr>
    <td class="header" style="padding: 25px 0; text-align: center;">
        <a href="{{ $url ?? config('app.url') }}"
            style="display: inline-block; color: #3d4852; font-size: 19px; font-weight: bold; text-decoration: none;">
            @if (isset($siteSettings['site_logo']) &&
                    !empty($siteSettings['site_logo']) &&
                    Storage::disk('public')->exists($siteSettings['site_logo']))
                <img src="{{ asset('storage/' . $siteSettings['site_logo']) }}" class="logo"
                    alt="{{ $siteSettings['site_name'] ?? '' }}"
                    style="max-height: 70px; border: 0; -ms-interpolation-mode: bicubic; vertical-align: middle;">
            @elseif(isset($siteSettings['site_name']))
                {{ $siteSettings['site_name'] }}
            @else
                {{ config('app.name', 'Laravel') }}
            @endif
        </a>
    </td>
</tr>
