@extends('layout.mainlayout')
@section('content')
@include('layout.toast')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Scans Logged from the fingerprint</h4>
                    <h6>Manage your logs</h6>
                </div>
            </div>
            <ul class="table-top-head">
                <li class="me-2">
                    <a data-bs-toggle="tooltip" onclick="window.print()" data-bs-placement="top" title="Pdf"><img src="{{URL::asset('build/img/icons/pdf.svg')}}" alt="img"></a>
                </li>
                <li class="me-2">
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Excel"><img src="{{URL::asset('build/img/icons/excel.svg')}}" alt="img"></a>
                </li>
                <li class="me-2">
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i class="ti ti-refresh"></i></a>
                </li>
                <li class="me-2">
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i class="ti ti-chevron-up"></i></a>
                </li>
            </ul>
       
        </div>
        <!-- /product list -->
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                <div class="search-set">
                 
                </div>
                <div class="d-flex table-dropdown my-xl-auto right-content align-items-center flex-wrap row-gap-3">
                  
                
                    <div class="dropdown">
                        <form method="GET" action="{{ route('zkteco_logs.index') }}" class="d-flex align-items-center gap-2">

                            <!-- Date filter -->
                            <input type="date" name="date" value="{{ request('date') }}" class="form-control">

                            <!-- Sort dropdown -->
                            <select name="sort" class="form-select">
                                <option value="">Sort By</option>
                                <option value="asc" {{ request('sort')=='asc' ? 'selected' : '' }}>Pickup Time ↑</option>
                                <option value="desc" {{ request('sort')=='desc' ? 'selected' : '' }}>Pickup Time ↓</option>
                            </select>

                            <button type="submit" class="btn btn-primary">Apply</button>
                            <a href="{{ route('zkteco_logs.index') }}" class="btn btn-secondary">Reset</a>
                        </form>
                    </div>
                </div>
            </div>
            <div class="container">
                <h2 class="mb-4">Fingerprint Logs</h2>

                <table class="table table-bordered" id="printableArea">
                    <thead>
                        <tr>
                            <th>UI</th>
                            <th>User</th>
                            <th>Arrival Time</th>
                            <th>Leaving Time</th>
                            <th>Log Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                            <tr>
                                <td>{{ $log->user_id }}</td>
                                <td>{{ $log->user->name ?? 'Unknown' }}</td>
                                <td>{{ $log->dropoff_time }}</td>
                                <td>{{ $log->pickup_time }}</td>
                                <td>{{ $log->log_date }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $logs->links('pagination::bootstrap-5') }}

            </div>
        </div>
        
    </div>
    <div class="footer d-sm-flex align-items-center justify-content-between border-top bg-white p-3">
        <p class="mb-0">&copy; JavaPA. All Right Reserved</p>
        <p>Designed &amp; Developed by <a href="javascript:void(0);" class="text-primary">JavaPA</a></p>
    </div>
</div>  
@endsection
