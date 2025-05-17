@props(['label'])

<div class="flex justify-between items-center text-sm py-1">
    <span class="font-semibold text-gray-700">{{ $label }}:</span>
    <span class="text-gray-800">{{ $slot }}</span>
</div>

