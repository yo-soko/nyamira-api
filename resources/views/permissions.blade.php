@can('view roles & permissions')
<?php $page = 'permissions'; ?>
@extends('layout.mainlayout')
@section('content')
@include('layout.toast')
@php
$actions = ['view', 'add', 'edit', 'delete'];
@endphp
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Permission</h4>
                    <h6>Manage your permissions</h6>
                </div>
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
                <li>
                    <div class="page-btn">
                        <a href="{{url('roles-permissions')}}" class="btn btn-primary"><i data-feather="arrow-left" class="me-2"></i> Back to Roles</a>
                    </div>
                </li>
            </ul>
        </div>
        <!-- /product list -->
        <div class="card">
            <div class="card-header">
                <div class="table-top mb-0">
                    <div class="search-set">
                        <div class="search-input">
                            <span class="btn-searchset"><i class="ti ti-search fs-14 feather-search"></i></span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <p class="mb-0 fw-medium text-gray-9 me-1">Role:</p>
                        <p>{{ $role->name }}</p>
                    </div>
                </div>
            </div>
            <form action="{{ route('roles.permissions.update', $role->id) }}" method="POST">
            @csrf
            @method('PUT')
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead class="thead-light">
                                <tr>
                                    <th class="no-sort">Module</th>
                                    <th>Allow All</th>
                                    @foreach($actions as $action)
                                        <th>{{ ucfirst($action) }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($groupedPermissions as $module => $actionSet)
                                    <tr>
                                        <td class="text-gray-9">{{ ucfirst($module) }}</td>
                                        <td class="py-3">
                                            <input type="checkbox" class="form-check-input allow-all" data-module="{{ $module }}">
                                        </td>
                                       @foreach ($actions as $action)
                                            <td class="py-3">
                                                <div class="form-check form-check-md">
                                                    @php
                                                        $permName = strtolower($action . ' ' . $module);
                                                    @endphp
                                                    <input class="form-check-input" type="checkbox"
                                                        name="permissions[]"
                                                        value="{{ $permName }}"
                                                        {{ $role->permissions->contains('name', $permName) ? 'checked' : '' }}>
                                                </div>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                 <div class="card-footer text-end">
        <button type="submit" class="btn btn-primary">Save Permissions</button>
    </div>
</form>   
        </div>
        <!-- /product list -->
    </div>
    <div class="footer d-sm-flex align-items-center justify-content-between border-top bg-white p-3">
        <p class="mb-0">&copy; JavaPA. All Right Reserved</p>
        <p>Designed &amp; Developed by <a href="javascript:void(0);" class="text-primary">JavaPA</a></p>
    </div>
</div>
<script>
document.querySelectorAll('.allow-all').forEach(checkbox => {
    checkbox.addEventListener('change', function () {
        const row = this.closest('tr');
        row.querySelectorAll('input[type=checkbox]').forEach(cb => {
            if (cb !== this) cb.checked = this.checked;
        });
    });
});
</script>=
@endsection
@endcan