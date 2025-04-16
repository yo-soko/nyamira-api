<div>
    <div class="row">
        <div class="col-xl-7 col-sm-12 col-12 d-flex">
            <div class="card flex-fill">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Purchase & Sales</h5>
                    <div class="graph-sets">
                        <ul class="mb-0">
                            <li>
                                <span>Sales</span>
                            </li>
                            <li>
                                <span>Purchase</span>
                            </li>
                        </ul>
                        <div class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle btn btn-sm btn-white"  data-bs-toggle="dropdown" aria-expanded="false">
                                2025
                            </a>
                            <ul class="dropdown-menu p-3">
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item">2025</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item">2022</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item">2021</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body pb-0">
                    <div id="sales_charts"></div>
                </div>
            </div>
        </div>

        <!-- Recent Products -->
        <div class="col-xl-5 col-sm-12 col-12 d-flex">
            <div class="card flex-fill default-cover mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Recent Products</h4>
                    <div class="view-all-link">
                        <a href="{{url('product-list')}}" class="link-default d-flex align-items-center">
                            View All<span class="ps-2 d-flex align-items-center"><i data-feather="arrow-right" class="feather-16"></i></span>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive dataview">
                        <table class="table dashboard-recent-products">
                            <thead>
                                <tr>
                                    <th class="bg-light">#</th>
                                    <th class="bg-light">Products</th>
                                    <th class="bg-light">Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <a href="{{url('product-list')}}" class="avatar avatar-lg me-3">
                                                <img src="{{URL::asset('build/img/products/stock-img-01.png')}}" alt="img">
                                            </a>
                                            <div>
                                                <h6 class="fw-medium"><a href="{{url('product-list')}}">Lenevo 3rd Generation</a></h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>$12500</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <a href="{{url('product-list')}}" class="avatar avatar-lg me-3">
                                                <img src="{{URL::asset('build/img/products/stock-img-06.png')}}" alt="img">
                                            </a>
                                            <div>
                                                <h6 class="fw-medium"><a href="{{url('product-list')}}">Bold V3.2</a></h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>$1600</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <a href="{{url('product-list')}}" class="avatar avatar-lg me-3">
                                                <img src="{{URL::asset('build/img/products/stock-img-02.png')}}" alt="img">
                                            </a>
                                            <div>
                                                <h6 class="fw-medium"><a href="{{url('product-list')}}">Nike Jordan</a></h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>$2000</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <a href="{{url('product-list')}}" class="avatar avatar-lg me-3">
                                                <img src="{{URL::asset('build/img/products/stock-img-03.png')}}" alt="img">
                                            </a>
                                            <div>
                                                <h6 class="fw-medium"><a href="{{url('product-list')}}">Apple Series 5 Watch</a></h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>$800</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Recent Products -->

    </div>

    <!-- Expired Products -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title">Expired Products</h4>
            <div class="view-all-link">
                <a href="{{url('expired-products')}}" class="link-default d-flex align-items-center">
                    View All<span class="ps-2 d-flex align-items-center"><i data-feather="arrow-right" class="feather-16"></i></span>
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive dataview">
                <table class="table dashboard-expired-products">
                    <thead>
                        <tr>
                            <th class="no-sort bg-light">
                                <label class="checkboxs">
                                    <input type="checkbox" id="select-all">
                                    <span class="checkmarks"></span>
                                </label>
                            </th>
                            <th class="bg-light">Product</th>
                            <th class="bg-light">SKU</th>
                            <th class="bg-light">Manufactured Date</th>
                            <th class="bg-light">Expired Date</th>
                            <th class="no-sort bg-light">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <label class="checkboxs">
                                    <input type="checkbox">
                                    <span class="checkmarks"></span>
                                </label>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <a href="javascript:void(0);" class="avatar avatar-lg me-2">
                                        <img src="{{URL::asset('build/img/products/expire-product-01.png')}}" alt="img">
                                    </a>
                                    <div>
                                        <h6 class="fw-medium"><a href="javascript:void(0);">Red Premium Handy</a></h6>
                                    </div>
                                </div>
                            </td>
                            <td>PT006</td>
                            <td>17 Jan 2023</td>
                            <td>29 Mar 2023</td>
                            <td class="action-table-data">
                                <div class="edit-delete-action">
                                    <a class="me-2 p-2" href="#">
                                        <i data-feather="edit" class="feather-edit"></i>
                                    </a>
                                    <a class=" confirm-text p-2" href="javascript:void(0);">
                                        <i data-feather="trash-2" class="feather-trash-2"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="checkboxs">
                                    <input type="checkbox">
                                    <span class="checkmarks"></span>
                                </label>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <a href="javascript:void(0);" class="avatar avatar-lg me-2">
                                        <img src="{{URL::asset('build/img/products/expire-product-02.png')}}" alt="img">
                                    </a>
                                    <div>
                                        <h6 class="fw-medium"><a href="javascript:void(0);">Iphone 14 Pro</a></h6>
                                    </div>
                                </div>
                            </td>
                            <td>PT007</td>
                            <td>22 Feb 2023</td>
                            <td>04 Apr 2023</td>
                            <td class="action-table-data">
                                <div class="edit-delete-action">
                                    <a class="me-2 p-2" href="#">
                                        <i data-feather="edit" class="feather-edit"></i>
                                    </a>
                                    <a class="confirm-text p-2" href="javascript:void(0);">
                                        <i data-feather="trash-2" class="feather-trash-2"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="checkboxs">
                                    <input type="checkbox">
                                    <span class="checkmarks"></span>
                                </label>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <a href="javascript:void(0);" class="avatar avatar-lg me-2">
                                        <img src="{{URL::asset('build/img/products/expire-product-03.png')}}" alt="img">
                                    </a>
                                    <div>
                                        <h6 class="fw-medium"><a href="javascript:void(0);">Black Slim 200</a></h6>
                                    </div>
                                </div>
                            </td>
                            <td>PT008</td>
                            <td>18 Mar 2023</td>
                            <td>13 May 2023</td>
                            <td class="action-table-data">
                                <div class="edit-delete-action">
                                    <a class="me-2 p-2" href="#">
                                        <i data-feather="edit" class="feather-edit"></i>
                                    </a>
                                    <a class=" confirm-text p-2" href="javascript:void(0);">
                                        <i data-feather="trash-2" class="feather-trash-2"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="checkboxs">
                                    <input type="checkbox">
                                    <span class="checkmarks"></span>
                                </label>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <a href="javascript:void(0);" class="avatar avatar-lg me-2">
                                        <img src="{{URL::asset('build/img/products/expire-product-04.png')}}" alt="img">
                                    </a>
                                    <div>
                                        <h6 class="fw-medium"><a href="javascript:void(0);">Woodcraft Sandal</a></h6>
                                    </div>
                                </div>
                            </td>
                            <td>PT009</td>
                            <td>29 Mar 2023</td>
                            <td>27 May 2023</td>
                            <td class="action-table-data">
                                <div class="edit-delete-action">
                                    <a class="me-2 p-2" href="#">
                                        <i data-feather="edit" class="feather-edit"></i>
                                    </a>
                                    <a class=" confirm-text p-2" href="javascript:void(0);">
                                        <i data-feather="trash-2" class="feather-trash-2"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="checkboxs">
                                    <input type="checkbox">
                                    <span class="checkmarks"></span>
                                </label>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <a href="javascript:void(0);" class="avatar avatar-lg me-2">
                                        <img src="{{URL::asset('build/img/products/stock-img-03.png')}}" alt="img">
                                    </a>
                                    <div>
                                        <h6 class="fw-medium"><a href="javascript:void(0);">Apple Series 5 Watch</a></h6>
                                    </div>
                                </div>
                            </td>
                            <td>PT010</td>
                            <td>24 Mar 2023</td>
                            <td>26 May 2023</td>
                            <td class="action-table-data">
                                <div class="edit-delete-action">
                                    <a class="me-2 p-2" href="#" data-bs-toggle="modal" data-bs-target="#edit-units">
                                        <i data-feather="edit" class="feather-edit"></i>
                                    </a>
                                    <a class=" confirm-text p-2" href="javascript:void(0);">
                                        <i data-feather="trash-2" class="feather-trash-2"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- /Expired Products -->  
</div>
