@if (session('success'))
    <div class="flash flash--success" role="alert">
        <i data-lucide="check-circle" aria-hidden="true"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if (session('error'))
    <div class="flash flash--error" role="alert">
        <i data-lucide="alert-circle" aria-hidden="true"></i>
        <span>{{ session('error') }}</span>
    </div>
@endif

@if ($errors->any())
    <div class="flash flash--error" role="alert">
        <i data-lucide="alert-circle" aria-hidden="true"></i>
        <div>
            <strong>Please fix the following:</strong>
            <ul style="margin: 0.5rem 0 0 1.25rem; list-style: disc;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
