@props([
    'action',
    'id' => 'page-search',
    'label' => 'Search',
    'placeholder' => 'Search…',
])

<form class="page-search" method="GET" action="{{ $action }}" role="search">
    <label for="{{ $id }}" class="page-search__label">{{ $label }}</label>
    <div class="page-search__inner">
        <span class="page-search__icon" aria-hidden="true"><i data-lucide="search"></i></span>
        <input
            type="search"
            id="{{ $id }}"
            name="q"
            class="page-search__input"
            value="{{ request('q') }}"
            placeholder="{{ $placeholder }}"
            autocomplete="off"
        >
        <button type="submit" class="btn btn--primary btn--sm page-search__submit">Search</button>
        @if (request()->filled('q'))
            <a href="{{ $action }}" class="btn btn--secondary btn--sm page-search__clear">Clear</a>
        @endif
    </div>
</form>
