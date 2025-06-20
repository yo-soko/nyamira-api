@extends('layout.mainlayout')
@section('content')

<h2>Add Teacher</h2>
<form action="{{ route('teachers.store') }}" method="POST">
    @csrf
    <div><label>First Name:</label><input type="text" name="first_name" required></div>
    <div><label>Last Name:</label><input type="text" name="last_name" required></div>
    <div><label>Date of Birth:</label><input type="date" name="date_of_birth" required></div>
    <div><label>Email:</label><input type="email" name="email" required></div>
    <div><label>Phone:</label><input type="text" name="phone" required></div>
    <div><label>ID Number:</label><input type="text" name="id_no" required></div>
    <div><label>Address:</label><textarea name="address" required></textarea></div>
    <div><label>Education Level:</label><input type="text" name="education_level" required></div>
    <div><label>Years of Experience:</label><input type="text" name="years_of_experience" required></div>
    <div>
        <label>Gender:</label>
        <select name="gender" required>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>
    </div>
    <div><label>Department (ID):</label><input type="number" name="department" required></div>
    <div>
        <label>Status:</label>
        <select name="status" required>
            <option value="1">Active</option>
            <option value="0">Inactive</option>
        </select>
    </div>

    <h4>Subjects and Classes</h4>
    <div id="teaching-area">
        <div class="teaching-entry">
            <select name="subject_class[0][subject_id]">
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                @endforeach
            </select>

            <select name="subject_class[0][schoolclass_id]">
                @foreach($schoolclasses as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <button type="button" onclick="addEntry()">+ Add More</button>
    <button type="submit">Save Teacher</button>
</form>

<hr>

<h2>Teachers List</h2>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Name</th><th>Email</th><th>ID No</th><th>DOB</th><th>Phone</th>
            <th>Address</th><th>Education</th><th>Experience</th><th>Gender</th>
            <th>Department</th><th>Status</th><th>Subjects & Classes</th>
        </tr>
    </thead>
    <tbody>
        @foreach($teachers as $teacher)
        <tr>
            <td>{{ $teacher->first_name }} {{ $teacher->last_name }}</td>
            <td>{{ $teacher->email }}</td>
            <td>{{ $teacher->id_no }}</td>
            <td>{{ $teacher->date_of_birth }}</td>
            <td>{{ $teacher->phone }}</td>
            <td>{{ $teacher->address }}</td>
            <td>{{ $teacher->education_level }}</td>
            <td>{{ $teacher->years_of_experience }}</td>
            <td>{{ $teacher->gender }}</td>
            <td>{{ $teacher->department }}</td>
            <td>{{ $teacher->status == 1 ? 'Active' : 'Inactive' }}</td>
            <td>
                <ul>
                    @foreach($teacher->subjects as $subject)
                        <li>{{ $subject->name }} - Class: {{ \App\Models\Schoolclass::find($subject->pivot->schoolclass_id)->name ?? 'N/A' }}</li>
                    @endforeach
                </ul>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<script>
    let index = 1;
    function addEntry() {
        const container = document.getElementById('teaching-area');
        const div = document.createElement('div');
        div.className = 'teaching-entry';
        div.innerHTML = `
            <select name="subject_class[${index}][subject_id]">
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                @endforeach
            </select>

            <select name="subject_class[${index}][schoolclass_id]">
                @foreach($schoolclasses as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                @endforeach
            </select>
        `;
        container.appendChild(div);
        index++;
    }
</script>

@endsection
