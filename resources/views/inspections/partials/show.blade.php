<div>
    <h5>Vehicle: {{ $inspection->vehicle->name ?? $inspection->vehicle->model }}</h5>
    <p><strong>Inspector:</strong> {{ $inspection->inspector->name }}</p>
    <p><strong>Date:</strong> {{ $inspection->inspection_date->format('Y-m-d H:i') }}</p>
    <p><strong>Odometer:</strong> {{ $inspection->odometer_reading ?? '-' }}</p>
    <p><strong>Status:</strong> {{ $inspection->status }}</p>
    <p><strong>Notes:</strong> {{ $inspection->notes ?? '—' }}</p>
    <hr>
    <h6>Checklist Items</h6>
    <ul class="list-group">
        @foreach($inspection->items as $item)
            <li class="list-group-item d-flex justify-content-between">
                <div>
                    <strong>{{ $item->item_name }}</strong> - {{ $item->status }}
                    @if($item->remark)
                        <br><small>{{ $item->remark }}</small>
                    @endif
                </div>
                @if($item->attachment)
                    <a href="{{ asset('storage/'.$item->attachment) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                        View File
                    </a>
                @endif
            </li>
        @endforeach
    </ul>
</div>
