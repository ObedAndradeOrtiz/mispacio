@props(['id'])

@php
    $id = $id ?? md5($attributes->wire('model'));
@endphp

<template x-teleport="body">
    <div x-data="{ show: @entangle($attributes->wire('model')) }" x-on:close.stop="show = false" x-on:keydown.escape.window="show = false" x-show="show"
        x-transition.opacity id="{{ $id }}"
        class="fixed inset-0 flex items-center justify-center overflow-y-auto"
        style="display:none; background-color: rgba(0,0,0,.5); z-index: 99999;">
        <div class="relative p-6 mx-auto overflow-y-auto bg-white rounded-lg"
            style="width: 100%; max-width: 900px; max-height: 70vh;">
            <button type="button" x-on:click="show = false"
                class="absolute top-0 right-0 p-2 mt-2 mr-2 text-gray-600 hover:text-gray-800">
                ✕
            </button>

            {{ $slot }}
        </div>
    </div>
</template>
