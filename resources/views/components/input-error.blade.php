@props(['messages'])

@if ($messages)
    <ul class="mt-2 text-sm text-[rgb(255,205,205)]">
        @foreach ($messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif