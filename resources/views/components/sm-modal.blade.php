@props(['id'])

@php
    $id = $id ?? md5($attributes->wire('model'));
@endphp

<div x-data="{ show: @entangle($attributes->wire('model')).defer }" x-on:close.stop="show = false" x-on:keydown.escape.window="show = false" x-show="show"
    id="{{ $id }}" class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto"
    style="display: none; background-color: rgba(0, 0, 0, 0.644);">

    <!-- Modal Background -->
    <div class="relative p-6 mx-auto overflow-y-auto bg-white rounded-lg shadow-lg"
        style="width: 90%; max-width: 900px; max-height: 80vh;">

        <!-- Close Button -->
        <label x-on:click="show = false"
            class="absolute top-0 right-0 p-2 mt-2 mr-2 text-gray-600 cursor-pointer hover:text-gray-800">
            <i class="fas fa-times-circle" style="font-size: 24px; color: #544b54ab;"></i> <!-- Font Awesome icon -->
        </label>

        <!-- Modal Content -->
        {{ $slot }}
    </div>
</div>
