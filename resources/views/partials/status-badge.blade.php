{{-- Shared status badge pill (design.md §4.6) --}}
{{-- Params: $status (kondisi or raw label), optional $label, optional $size ('md'|'sm'), optional $shadow (bool) --}}
@php
    $kondisiColors = [
        'Baik' => 'bg-green-100 text-green-800 border border-green-200',
        'Rusak Ringan' => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
        'Rusak Berat' => 'bg-red-100 text-red-800 border border-red-200',
    ];
    $sizeClass = ($size ?? 'md') === 'sm'
        ? 'px-2 py-0.5 inline-flex text-[10px] font-semibold rounded'
        : 'px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full';
    $colorClass = $kondisiColors[$status] ?? 'bg-gray-100 text-gray-700 border border-gray-200';
@endphp
<span class="{{ $sizeClass }} {{ $colorClass }}{{ !empty($shadow) ? ' shadow-sm' : '' }}">{{ $label ?? $status }}</span>
