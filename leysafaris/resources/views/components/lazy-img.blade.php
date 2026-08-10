@props([
    'src',
    'alt',
    'width' => 600,
    'height' => 400,
    'class' => '',
])

<x-optimized-img
    :src="$src"
    :alt="$alt"
    :width="$width"
    :height="$height"
    :class="$class"
    :priority="false"
    {{ $attributes }}
/>
