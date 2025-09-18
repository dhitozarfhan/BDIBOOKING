@props(['item', 'type', 'selectedId', 'level' => 0])

@php
    $indent = str_repeat('— ', $level);
    $displayText = $indent . $item->code . ' - ' . $item->name;
@endphp

<option value="{{ $item->id }}" {{ $selectedId == $item->id ? 'selected' : '' }}>
    {{ $displayText }}
</option>

@if($item->children && $item->children->count() > 0)
    @foreach($item->children as $child)
        @include('filament.pages.archive-tree-option', [
            'item' => $child, 
            'type' => $type, 
            'selectedId' => $selectedId,
            'level' => $level + 1
        ])
    @endforeach
@endif