@extends('layout.mainlayout')
@section('content')
@include('layout.toast')

<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
           <div class="page-title">
                    <h4>Terms</h4>
                    <h6>Manage your Terms</h6>
                </div>
                <ul class="table-top-head">
                    <li>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Pdf"><img src="{{URL::asset('build/img/icons/pdf.svg')}}" alt="img"></a>
                    </li>
                    <li>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Excel"><img src="{{URL::asset('build/img/icons/excel.svg')}}" alt="img"></a>
                    </li>						
                    <li>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i class="ti ti-refresh"></i></a>
                    </li>
                    <li>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i class="ti ti-chevron-up"></i></a>
                    </li>
                </ul>
             @hasanyrole('admin|developer|manager|director|supervisor|class_teacher')
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSubjectModal">Add Subject</button>
            @endhasrole
        </div>


        <div class="card">
            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table datatable">
                        <thead class="thead-light" >
                            <tr>
                                <th>#</th>
                                <th>Subject Code</th>
                                <th>Subject Name</th>
                                <th>Status</th>
                                @hasanyrole('admin|developer|manager|director|supervisor')
                                <th>Actions</th>
                                @endhasanyrole
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subjects as $key => $subject)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $subject->subject_code }}</td>
                                <td>{{ $subject->subject_name }}</td>
                                <td>{{ $subject->status ? 'Active' : 'Inactive' }}</td>
                                @hasanyrole('admin|developer|manager|director|supervisor')
                                <td>
                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editSubjectModal{{ $subject->id }}">Edit</button>
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteSubjectModal{{ $subject->id }}"> Delete
                                    </button>
                                </td>
                                @endhasanyrole
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editSubjectModal{{ $subject->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <form action="{{ route('subjects.update', $subject->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Subject</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group mb-2">
                                                    <label>Subject Code</label>
                                                    <input type="text" name="subject_code" value="{{ $subject->subject_code }}" class="form-control" required>
                                                </div>
                                                <div class="form-group mb-2">
                                                    <label>Subject Name</label>
                                                    <input type="text" name="subject_name" value="{{ $subject->subject_name }}" class="form-control" required>
                                                </div>
                                                <div class="form-group mb-2">
                                                    <label>Status</label>
                                                    <select name="status" class="form-control">
                                                        <option value="1" {{ $subject->status ? 'selected' : '' }}>Active</option>
                                                        <option value="0" {{ !$subject->status ? 'selected' : '' }}>Inactive</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-primary" type="submit">Update</button>
                                                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- Delete Confirmation Modal -->
                            <div class="modal fade" id="deleteSubjectModal{{ $subject->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <form action="{{ route('subjects.destroy', $subject->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title text-white">Confirm Deletion</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Are you sure you want to delete the subject <strong>{{ $subject->subject_name }}</strong>?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>   
        </div>
      
    </div>


    <!-- Add Subject Modal -->
    <div class="modal fade" id="addSubjectModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('subjects.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Subject</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-2">
                            <label>Subject Code</label>
                            <input type="text" name="subject_code" class="form-control" required>
                        </div>
                        <div class="form-group mb-2">
                            <label>Subject Name</label>
                            <input type="text" name="subject_name" class="form-control" required>
                        </div>
                        <div class="form-group mb-2">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="1" selected>Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">Save</button>
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="footer d-sm-flex align-items-center justify-content-between border-top bg-white p-3">
        <p class="mb-0">&copy; JavaPA. All Right Reserved</p>
        <p>Designed &amp; Developed by <a href="javascript:void(0);" class="text-primary">JavaPA</a></p>
    </div>


</div>            
@endsection
