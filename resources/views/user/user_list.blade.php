@extends('layouts.app')
@push('breadcrumb')
    <li class="breadcrumb-item active">User</li>
@endpush
@push('page-title')
    User
@endpush
@push('bottom_style')
    <style>
        /* Basic styles for the badge */
        .badge {
            display: inline-block;
            padding: 0.25em 0.4em;
            font-size: 75%;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.375rem;
        }

        /* Primary badge styles */
        .badge-primary {
            color: #fff;
            background-color: #0d6efd;
        }

        /* Hover and focus states */
        .badge-primary:hover,
        .badge-primary:focus {
            background-color: #0b5ed7;
        }

        /* Optional: Styles for rounded pill badges */
        .badge.rounded-pill {
            border-radius: 10rem;
        }

    </style>
@endpush
@section('content')
    <div class="page-content-width-full">
        <div
            class="content-layout content-width-full user-data-content js-ak-DataTable js-ak-delete-container js-ak-content-layout"
            data-id="user"
            data-delete-modal-action="">
            <div class="content-element">
                <div class="content-header">
                    <div class="header">
                        <h3>User List</h3>
                    </div>
                    <div class="action">
                        <div class="left">
                            <form class="search-container">
                                <input name="user_source" value="user" type="hidden"/>
                                <input name="user_length" value="{{Request()->query("user_length")}}" type="hidden"/>
                                <div class="search">
                                    <input type="text" autocomplete="off" placeholder="Search" name="user_search"
                                           value="{{(Request()->query("user_source") == "user")?Request()->query("user_search")??"":""}}"
                                           class="form-input js-ak-search-input">
                                    <button class="search-button" draggable="false">
                                        @includeIf("layouts.icons.search_icon")
                                    </button>
                                    @if(Request()->query("user_source") == "user" && Request()->query("user_search"))
                                        <div class="reset-search js-ak-reset-search">
                                            @includeIf("layouts.icons.reset_search_icon")
                                        </div>
                                    @endif
                                </div>
                            </form>
                        </div>
                        <div class="right">
                            @can('delete-user')
                                <a href="{{route('user.create')}}" class="button primary-button add-new"
                                   draggable="false">
                                    @includeIf("layouts.icons.add_new_icon")
                                    Add New
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>
                <div class="content table-content">
                    <table class="table js-ak-content">
                        <thead>
                        <tr data-sort-method='thead'>
                            <th class="table-id" data-sort-method="number">ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Is Active</th>
                            <th class="no-sort manage-th" data-orderable="false">
                                <div class="manage-links">
                                </div>
                            </th>
                        </tr>
                        </thead>
                        <tbody class="">
                        @foreach($user_list as $data)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$data->name}}</td>
                                <td>{{$data->email}}</td>
                                <td>{{$data->phone}}</td>
                                <td>
                                    @foreach($data->roles as $role)
                                        <span class="badge bg-primary">{{$role->name}}</span>
                                    @endforeach
                                </td>
                                <td>{{$data->is_active == 1 ?'Active':'De-Active' }}</td>

                                <td class="manage-td">
                                    <div class="manage-links">
                                        @canany(['add-user','edit-user'])
                                            <a class="btn btn-sm btn-link text-decoration-none"
                                               href="{{ route('give_permission', ['user_id' => $data->id]) }}">@includeIf("layouts.icons.map_icon")</a>

                                        @endcanany
                                        @can('edit-user')
                                            <a href="{{route('user.edit',$data->id)}}" class="edit-link"
                                               draggable="false">@includeIf("layouts.icons.edit_icon")</a>
                                        @endcan
                                        @can('delete-user')
                                            <a data-link="{{route('user.destroy',$data->id)}}"
                                               href="javascript:void(0);" data-id="{{$data->id}}"
                                               class="delete-link js-ak-delete-link"
                                               draggable="false">@includeIf("layouts.icons.delete_icon")</a>
                                        @endcan
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
    </div>
@endsection
