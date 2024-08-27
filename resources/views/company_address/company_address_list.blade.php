@extends('layouts.app')
@push('breadcrumb')
    <li class="breadcrumb-item active">Company Address</li>
@endpush

@section('content')
    <div class="page-content-width-full">
        <div
            class="content-layout content-width-full company_address-data-content js-ak-DataTable js-ak-delete-container js-ak-content-layout"
            data-id="company_address"
            data-delete-modal-action="">
            <div class="content-element">
                <div class="content-header">
                    <div class="header">
                        <h3>Company Address List</h3>
                    </div>
                    <div class="action">
                        <div class="left">
                            <form class="search-container">
                                <input name="company_address_source" value="company_address" type="hidden"/>
                                <input name="company_address_length"
                                       value="{{Request()->query("company_address_length")}}" type="hidden"/>
                                <div class="search">
                                    <input type="text" autocomplete="off" placeholder="Search"
                                           name="company_address_search"
                                           value="{{(Request()->query("company_address_source") == "company_address")?Request()->query("company_address_search")??"":""}}"
                                           class="form-input js-ak-search-input">
                                    <button class="search-button" draggable="false">
                                        @includeIf("layouts.icons.search_icon")
                                    </button>
                                    @if(Request()->query("company_address_source") == "company_address" && Request()->query("company_address_search"))
                                        <div class="reset-search js-ak-reset-search">
                                            @includeIf("layouts.icons.reset_search_icon")
                                        </div>
                                    @endif
                                </div>
                            </form>
                        </div>
                        @can('add-CompanyAddress')
                            <div class="right">
                                <a href="{{route('company_address.create')}}" class="button primary-button add-new"
                                   draggable="false">
                                    @includeIf("layouts.icons.add_new_icon")
                                    Add New
                                </a>
                            </div>
                        @endcan
                    </div>
                </div>
                <div class="content table-content">
                    <table class="table js-ak-content">
                        <thead>
                        <tr data-sort-method='thead'>
                            <th class="table-id" data-sort-method="number">ID</th>
                            <th>Company</th>
                            <th>Address Type</th>
                            <th>Address</th>
                            <th>Country</th>
                            <th>State</th>
                            <th>District</th>
                            <th>City</th>
                            <th>Pin Code</th>
                            <th class="no-sort manage-th" data-orderable="false">
                                <div class="manage-links">

                                </div>
                            </th>
                        </tr>
                        </thead>
                        <tbody class="">
                        @foreach($company_address_list as $data)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{ $data->company->company_name ?? "" }}</td>
                                <td>{{ $data->address_type }}</td>
                                <td>{!! $data->address !!}</td>
                                <td>{{ $data->country->country_name ?? "" }}</td>
                                <td>{{ $data->state->state_name ?? "" }}</td>
                                <td>{{ $data->district->district_name ?? "" }}</td>
                                <td>{{ $data->cityvillage->city_village_name ?? "" }}</td>
                                <td>{{ $data->pin_code }}</td>

                                <td class="manage-td">
                                    <div class="manage-links">
                                        @can('edit-CompanyAddress')
                                            <a href="{{route('company_address.edit',$data->id)}}" class="edit-link"
                                               draggable="false">@includeIf("layouts.icons.edit_icon")</a>
                                        @endcan
                                        @can('delete-CompanyAddress')
                                            <a data-link="{{route('company_address.destroy',$data->id)}}"
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
