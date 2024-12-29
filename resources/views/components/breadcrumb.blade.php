<nav area-label="Breadcrumb">
    <ol>
        @foreach ($breadcrumbs as $breadcrumb)
            @if ($breadcrumb['url'])
                <li><a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['name'] }}</a></li>
            @else
            <li>{{ $breadcrumb['name'] }}</li>
            @endif
        @endforeach
    </ol>
</nav>