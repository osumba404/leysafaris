@props([
    'viewUrl' => null,
    'editUrl' => null,
    'editModal' => null,
    'editModalData' => null,
    'deleteUrl' => null,
    'deleteConfirm' => 'Delete this item?',
    'extraUrl' => null,
    'extraIcon' => 'receipt',
    'extraTitle' => 'Open',
])

<div class="admin-table__actions">
    @if ($viewUrl)
        <a href="{{ $viewUrl }}" class="admin-btn admin-btn--icon" title="View" aria-label="View"><i data-lucide="eye"></i></a>
    @endif
    @if ($editUrl)
        <a href="{{ $editUrl }}" class="admin-btn admin-btn--icon" title="Edit" aria-label="Edit"><i data-lucide="pencil"></i></a>
    @endif
    @if ($editModal && $editModalData)
        <button type="button" class="admin-btn admin-btn--icon" title="Edit" aria-label="Edit" data-modal-edit="{{ $editModal }}" data-item='@json($editModalData)'><i data-lucide="pencil"></i></button>
    @endif
    @if ($extraUrl)
        <a href="{{ $extraUrl }}" class="admin-btn admin-btn--icon" title="{{ $extraTitle }}" aria-label="{{ $extraTitle }}"><i data-lucide="{{ $extraIcon }}"></i></a>
    @endif
    @if ($deleteUrl)
        <form action="{{ $deleteUrl }}" method="POST" class="admin-inline-form" onsubmit="return confirm(@json($deleteConfirm))">
            @csrf
            @method('DELETE')
            <button type="submit" class="admin-btn admin-btn--icon admin-btn--icon-danger" title="Delete" aria-label="Delete"><i data-lucide="trash-2"></i></button>
        </form>
    @endif
</div>
