<form action="{{ route('vehicles.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
    @csrf

    {{-- Identification --}}
    <h5 class="mt-4">Identification</h5>
    <div class="col-md-6">
        <label class="form-label">Vehicle Name *</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">VIN/SN</label>
        <input type="text" name="vin" class="form-control">
    </div>
    <div class="col-md-6">
        <label class="form-label">License Plate *</label>
        <input type="text" name="license_plate" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Type</label>
        <select name="type" class="form-select select2">
            <option value="">-- Select Type --</option>
            <option>Car</option>
            <option>Truck</option>
            <option>Bus</option>
            <option>Motorcycle</option>
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Fuel Type</label>
        <select name="fuel_type" class="form-select select2">
            <option value="">-- Select Fuel --</option>
            <option>Petrol</option>
            <option>Diesel</option>
            <option>Hybrid</option>
            <option>Electric</option>
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Year</label>
        <input type="number" name="year" min="1950" max="{{ date('Y')+1 }}" class="form-control">
    </div>
    <div class="col-md-6">
        <label class="form-label">Make</label>
        <input type="text" name="make" class="form-control">
    </div>
    <div class="col-md-6">
        <label class="form-label">Model</label>
        <input type="text" name="model" class="form-control">
    </div>
    <div class="col-md-6">
        <label class="form-label">Trim</label>
        <input type="text" name="trim" class="form-control">
    </div>

    {{-- Registration & Classification --}}
    <h5 class="mt-4">Registration & Classification</h5>
    <div class="col-md-6">
        <label class="form-label">Registration State</label>
        <input type="text" name="registration_state" class="form-control">
    </div>
    <div class="col-md-6">
        <label class="form-label">Status</label>
        <select name="status" class="form-select select2">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
            <option value="sold">Sold</option>
            <option value="scrapped">Scrapped</option>
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Group</label>
        <input type="text" name="group" class="form-control">
    </div>
    <div class="col-md-6">
        <label class="form-label">Ownership</label>
        <select name="ownership" class="form-select select2">
            <option value="owned">Owned</option>
            <option value="leased">Leased</option>
            <option value="hired">Hired</option>
        </select>
    </div>

    {{-- Specifications --}}
    <h5 class="mt-4">Specifications</h5>
    <div class="col-md-6">
        <label class="form-label">Color</label>
        <input type="text" name="color" class="form-control">
    </div>
    <div class="col-md-6">
        <label class="form-label">Body Type</label>
        <input type="text" name="body_type" class="form-control">
    </div>
    <div class="col-md-6">
        <label class="form-label">Body Subtype</label>
        <input type="text" name="body_subtype" class="form-control">
    </div>
    <div class="col-md-6">
        <label class="form-label">MSRP (KSh)</label>
        <input type="number" name="msrp" class="form-control" step="0.01">
    </div>
    <div class="col-md-6">
        <label class="form-label">Photo</label>
        <input type="file" name="photo" class="form-control">
    </div>
    <div class="col-12">
        <label class="form-label">Labels (comma separated)</label>
        <input type="text" name="labels" class="form-control" placeholder="e.g. School Bus, VIP">
    </div>

    {{-- Lifecycle --}}
    <h5 class="mt-4">Lifecycle</h5>
    <div class="col-md-4">
        <label class="form-label">Purchase Date</label>
        <input type="date" name="purchase_date" class="form-control">
    </div>
    <div class="col-md-4">
        <label class="form-label">Purchase Price</label>
        <input type="number" name="purchase_price" step="0.01" class="form-control">
    </div>
    <div class="col-md-4">
        <label class="form-label">Retirement Date</label>
        <input type="date" name="retirement_date" class="form-control">
    </div>

    {{-- Financial --}}
    <h5 class="mt-4">Financial</h5>
    <div class="col-md-6">
        <label class="form-label">Insurance Policy No.</label>
        <input type="text" name="insurance_policy_number" class="form-control">
    </div>
    <div class="col-md-6">
        <label class="form-label">Insurance Expiry</label>
        <input type="date" name="insurance_expiry" class="form-control">
    </div>
    <div class="col-12">
        <label class="form-label">Loan Details</label>
        <textarea name="loan_details" rows="3" class="form-control"></textarea>
    </div>

    {{-- Submit --}}
    <div class="col-12 text-end">
        <button type="submit" class="btn btn-primary px-5">Save Vehicle</button>
    </div>
</form>
