@props(['status'])

@if ($status)
    <div class="mb-4 font-medium text-sm text-[rgb(255,205,205)]">
        {{ $status }}
    </div>
@endif