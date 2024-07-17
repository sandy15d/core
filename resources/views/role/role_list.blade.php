@extends('layouts.app')
@push('breadcrumb')
    <li class="breadcrumb-item active">Roles</li>
@endpush
@push('page-title')
    Roles
@endpush

@push('upper_style')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
          crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous">
    </script>
@endpush
@push('bottom_style')
    <style>


        /* Primary badge styles */
        .badge-primary {
            color: #fff;
            background-color: rgb(122 91 121) !important;
        }

        /* Hover and focus states */
        .badge-primary:hover,
        .badge-primary:focus {
            background-color: rgb(54 39 54) !important;
        }

        /* Optional: Styles for rounded pill badges */
        .badge1.rounded-pill {
            border-radius: 10rem;
        }

    </style>
@endpush
@section('content')
    <div class="page-content-width-full">
        <div
            class="content-layout content-width-full role-data-content js-ak-DataTable js-ak-delete-container js-ak-content-layout"
            data-id="role"
            data-delete-modal-action="">
            <div class="content-element">
                <div class="content-header">
                    <div class="header">
                        <h3>Role List</h3>
                    </div>
                    <div class="action">
                        <div class="left">
                            <form class="search-container">
                                <input name="role_source" value="role" type="hidden"/>
                                <input name="role_length" value="{{Request()->query("role_length")}}"
                                       type="hidden"/>
                                <div class="search">
                                    <input type="text" autocomplete="off" placeholder="Search" name="role_search"
                                           value="{{(Request()->query("role_source") == "role")?Request()->query("role_search")??"":""}}"
                                           class="form-input js-ak-search-input">
                                    <button class="search-button" draggable="false">
                                        @includeIf("layouts.icons.search_icon")
                                    </button>
                                    @if(Request()->query("role_source") == "role" && Request()->query("role_search"))
                                        <div class="reset-search js-ak-reset-search">
                                            @includeIf("layouts.icons.reset_search_icon")
                                        </div>
                                    @endif
                                </div>
                            </form>
                        </div>
                        <div class="right">
                            <a href="javascript:void(0);" class="button primary-button add-new"
                               draggable="false" data-bs-toggle="modal" data-bs-target="#roleModal">
                                @includeIf("layouts.icons.add_new_icon")
                                Add New
                            </a>
                        </div>
                    </div>
                </div>
                <div class="content table-content">
                    <table class="table js-ak-content">
                        <thead>
                        <tr data-sort-method='thead'>
                            <th class="table-id" data-sort-method="number">ID</th>
                            <th>Name</th>
                            <th>Permission</th>
                            <th>Is Active</th>
                            <th class="no-sort manage-th" data-orderable="false">
                                <div class="manage-links">
                                </div>
                            </th>
                        </tr>
                        </thead>
                        <tbody class="">
                        @foreach($role_list as $data)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$data->name}}</td>
                                <td style="white-space: normal;width: 60%;">
                                    @foreach($data->permissions as $permission)
                                        <span class="badge badge-primary"
                                              style="font-size: 12px;">{{ $permission->name }}</span>
                                    @endforeach
                                </td>
                                <td>{{$data->is_active == 1 ?'Active':'De-Active' }}</td>

                                <td class="manage-td">
                                    <div class="manage-links">
                                        <a href="{{route('role.edit',$data->id)}}" class="edit-link"
                                           draggable="false">@includeIf("layouts.icons.edit_icon")</a>
                                        <a data-link="{{route('role.destroy',$data->id)}}"
                                           href="javascript:void(0);" data-id="{{$data->id}}"
                                           class="delete-link js-ak-delete-link"
                                           draggable="false">@includeIf("layouts.icons.delete_icon")</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="content-footer">
                    <div class="left">
                        <div class="change-length js-ak-table-length-DataTable"></div>
                    </div>
                    <div class="right">
                        <div class="content-pagination">
                            <nav class="pagination-container">
                                <div class="pagination-content">
                                    <div class="pagination-info js-ak-pagination-info"></div>
                                    <div class="pagination-box-data-table js-ak-pagination-box"></div>
                                </div>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        @includeIf("layouts.delete_modal_confirm")
        <!-- Modal -->
        <div class="modal fade" id="roleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
             aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X
                        </button>
                    </div>
                    <!-- Add role form -->
                    <form id="addRoleForm" class="row g-3" action="{{route('role.store')}}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="col-12 mb-4">
                                <label class="form-label" for="role_name">Role Name</label>
                                <input type="text" id="role_name" name="role_name" class="form-control"
                                       placeholder="Enter a role name" tabindex="-1"/>
                                <span class="text-danger error-text role_name_error"></span>
                            </div>
                            <div class="col-12">

                                <!-- Permission table -->
                                @foreach($permissions as $permission => $value)
                                    <div class="row mb-2">
                                        <div class="col-lg-12 mb-3">
                                            <h6 style="width:280px; height: 40px;padding: 10px; border-radius: 0px 20px 0px 0px;background-color: #405189;color: #fff;line-height: 25px;"
                                                class="fw-semibold bg-light-success border-bottom border-primary mb-0">
                                                    <span class=""><input type="checkbox"
                                                                          class="form-check-input {{$permission}}"
                                                                          onclick="checkAllPermissin('{{$permission}}');"> </span>{{ mb_strtoupper($permission) }}
                                            </h6>
                                            <div class="card-body"
                                                 style="background-color: #f3f3f3;padding: 8px;border-bottom: 1px solid #ddd;">
                                                <div class="row">
                                                    @foreach($value as $k => $v)
                                                        <div class="col-3 mt-1">
                                                            <div class="form-check">
                                                                <input type="checkbox" name="permission[]"
                                                                       class="form-check-input {{$permission}}"
                                                                       id="{{$v['id']}}" value="{{$v['id']}}">
                                                                <label class="form-check-label"
                                                                       for="{{$v['id']}}">{{$v['name']}}</label>
                                                            </div>
                                                        </div>

                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <!-- Permission table -->
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="button danger-button" data-bs-dismiss="modal">Close
                            </button>
                            <button type="submit" class="button primary-button">Save</button>
                        </div>

                    </form>
                    <!--/ Add role form -->
                </div>
            </div>
        </div>
    </div>
@endsection
@push('bottom_script')
        <script>
            function checkAllPermissin(permission) {
                $('.' + permission).prop('checked', $('.' + permission + ':first').prop('checked'));
            }

            $('#roleModal').on('hidden.bs.modal', function () {
                $('#addRoleForm')[0].reset();
                $('.error-text').text('');
            });

        </script>
    @endpush
