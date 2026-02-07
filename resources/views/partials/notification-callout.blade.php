@props([
    'type' => 'success',
    'message' => '',
    'heading' => null,
    'dismissible' => true,
])

@php
    $variant = $type === 'danger' ? 'danger' : 'success';
    $icon = $type === 'danger' ? 'x-circle' : 'check-circle';
    $defaultHeading = $type === 'danger' ? __('Error') : __('Success');
    $heading = $heading ?? $defaultHeading;
@endphp

<div
    x-data="{ visible: true }"
    x-show="visible"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="mb-6"
>
    <flux:callout
        :variant="$variant"
        :icon="$icon"
        :heading="$heading"
    >
        <flux:callout.text>{{ $message }}</flux:callout.text>
        @if($dismissible)
            <x-slot name="controls">
                <flux:button
                    icon="x-mark"
                    variant="ghost"
                    x-on:click="visible = false"
                    aria-label="{{ __('Dismiss') }}"
                />
            </x-slot>
        @endif
    </flux:callout>
</div>
