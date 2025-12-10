@props(['column', 'label'])

@php
    $currentSort = request('sort_by');
    $currentDirection = request('sort_direction', 'asc');
    $isActive = $currentSort === $column;
    $newDirection = $isActive && $currentDirection === 'asc' ? 'desc' : 'asc';
@endphp

<th {{ $attributes }}>
    <a href="{{ request()->fullUrlWithQuery(['sort_by' => $column, 'sort_direction' => $newDirection]) }}" 
       class="flex items-center gap-1 hover:text-primary {{ $isActive ? 'text-primary font-semibold' : '' }}">
        {{ $label }}
        @if($isActive)
            <i class="ri-arrow-{{ $currentDirection === 'asc' ? 'up' : 'down' }}-line text-primary"></i>
        @else
            <i class="ri-arrow-up-down-line opacity-30"></i>
        @endif
    </a>
</th>
