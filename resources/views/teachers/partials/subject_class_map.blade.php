{{-- Place "Add" button after the loop --}}
    <div class="mt-2">
        <button type="button" class="btn btn-success add-subject-class">
            <i class="ti ti-plus"></i> Add Subject & Class
        </button>
    </div>
@foreach($teacher->subjects as $loopIndex => $subject)
    @php $class_id = $subject->pivot->class_id; @endphp
    <div class="row align-items-end teaching-entry mb-2">
        <div class="col-md-5">
            <select name="subject_class[{{ $loopIndex }}][subject_id]" class="form-select" required>
                <option value="" disabled>Select Subject</option>
                @foreach($subjects as $subj)
                    <option value="{{ $subj->id }}" {{ $subj->id == $subject->id ? 'selected' : '' }}>
                        {{ $subj->subject_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-5">
            <select name="subject_class[{{ $loopIndex }}][class_id]" class="form-select" required>
                <option value="" disabled>Select Class</option>
                @foreach($schoolclasses as $class)
                    <option value="{{ $class->id }}" {{ $class->id == $class_id ? 'selected' : '' }}>
                        {{ $class->level->level_name ?? '' }} {{ $class->stream->name ?? '' }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <button type="button" class="btn btn-danger remove-subject-class"><i class="ti ti-minus"></i></button>
        </div>
    </div>

@endforeach


