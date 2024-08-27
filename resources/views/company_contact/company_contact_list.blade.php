@extends('layouts.app')
@push('breadcrumb')
    <li class="breadcrumb-item active">Company Contact</li>
@endpush

@section('content')
     <div class="page-content-width-full">
            <div
                class="content-layout content-width-full company_contact-data-content js-ak-DataTable js-ak-delete-container js-ak-content-layout"
                data-id="company_contact"
                data-delete-modal-action="">
                <div class="content-element">
                    <div class="content-header">
                        <div class="header">
                            <h3>Company Contact List</h3>
                        </div>
                        <div class="action">
                            <div class="left">
                                <form class="search-container">
                                    <input name="company_contact_source" value="company_contact" type="hidden"/>
                                    <input name="company_contact_length" value="{{Request()->query("company_contact_length")}}" type="hidden"/>
                                    <div class="search">
                                        <input type="text" autocomplete="off" placeholder="Search" name="company_contact_search"
                                               value="{{(Request()->query("company_contact_source") == "company_contact")?Request()->query("company_contact_search")??"":""}}"
                                               class="form-input js-ak-search-input">
                                        <button class="search-button" draggable="false">
                                            @includeIf("layouts.icons.search_icon")
                                        </button>
                                        @if(Request()->query("company_contact_source") == "company_contact" && Request()->query("company_contact_search"))
                                            <div class="reset-search js-ak-reset-search">
                                                @includeIf("layouts.icons.reset_search_icon")
                                            </div>
                                        @endif
                                    </div>
                                </form>
                            </div>
                            @can('add-CompanyContact')
                            <div class="right">
                                <a href="{{route('company_contact.create')}}" class="button primary-button add-new"
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
                                   <th>Company</th>   <th>Company Address</th>   <th>Contact Type</th>   <th>Contact Person</th>   <th>Designation</th>   <th>Phone One</th>   <th>Phone Two</th>   <th>Is Active</th>
                                <th class="no-sort manage-th" data-orderable="false">
                                    <div class="manage-links">

                                    </div>
                                </th>
                            </tr>
                            </thead>
                            <tbody class="">
                                @foreach($company_contact_list as $data)
                                   <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{ $data->company->company_name ?? "" }}</td>
<td>{{ $data->companyaddress->address_type ?? "" }}</td>
<td>{{ $data->contact_type }}</td>
<td>{{ $data->contact_person }}</td>
<td>{{ $data->designation }}</td>
<td>{{ $data->phone_one }}</td>
<td>{{ $data->phone_two }}</td>
<td>{{ $data->is_active }}</td>

                                         <td class="manage-td">
                                            <div class="manage-links">
                                            @can('edit-CompanyContact')
                                                <a href="{{route('company_contact.edit',$data->id)}}" class="edit-link"
                                                   draggable="false">@includeIf("layouts.icons.edit_icon")</a>
                                            @endcan
                                            @can('delete-CompanyContact')
                                                <a data-link="{{route('company_contact.destroy',$data->id)}}"
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
