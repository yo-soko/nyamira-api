<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999">
    @foreach (['success', 'error', 'warning', 'info'] as $msg)
        @if(session($msg))
            @php
                $bgColor = match($msg) {
                    'success' => 'bg-success text-white',
                    'error' => 'bg-danger text-white',
                    'warning' => 'bg-warning text-dark',
                    'info' => 'bg-info text-dark',
                    default => 'bg-secondary text-white'
                };
            @endphp
            <div class="toast align-items-center {{ $bgColor }} border-0 mb-2"
                 role="alert" aria-live="assertive" aria-atomic="true"
                 data-bs-autohide="true" data-bs-delay="10000"> {{-- 10s --}}
                <div class="d-flex">
                    <div class="toast-body">
                        {{ session($msg) }}
                    </div>
                    <button type="button" class="btn-close {{ $msg == 'warning' || $msg == 'info' ? '' : 'btn-close-white' }} me-2 m-auto"
                            data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        @endif
    @endforeach

    {{-- Handle validation errors --}}
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="toast align-items-center bg-danger text-white border-0 mb-2"
                 role="alert" aria-live="assertive" aria-atomic="true"
                 data-bs-autohide="true" data-bs-delay="10000"> {{-- also 10s --}}
                <div class="d-flex">
                    <div class="toast-body">
                        {{ $error }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto"
                            data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        @endforeach
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toastElements = document.querySelectorAll('.toast');
        toastElements.forEach(function (toastEl) {
            const toast = new bootstrap.Toast(toastEl, {
                autohide: true,
                delay: 10000 // 10 seconds
            });
            toast.show();
        });
    });
</script>
