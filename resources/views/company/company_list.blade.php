@extends('layouts.app')
@push('breadcrumb')
    <li class="breadcrumb-item active">Company</li>
@endpush

@section('content')
     <div class="page-content-width-full">
            <div
                class="content-layout content-width-full company-data-content js-ak-DataTable js-ak-delete-container js-ak-content-layout"
                data-id="company"
                data-delete-modal-action="">
                <div class="content-element">
                    <div class="content-header">
                        <div class="header">
                            <h3>Company List</h3>
                        </div>
                        <div class="action">
                            <div class="left">
                                <form class="search-container">
                                    <input name="company_source" value="company" type="hidden"/>
                                    <input name="company_length" value="{{Request()->query("company_length")}}" type="hidden"/>
                                    <div class="search">
                                        <input type="text" autocomplete="off" placeholder="Search" name="company_search"
                                               value="{{(Request()->query("company_source") == "company")?Request()->query("company_search")??"":""}}"
                                               class="form-input js-ak-search-input">
                                        <button class="search-button" draggable="false">
                                            @includeIf("layouts.icons.search_icon")
                                        </button>
                                        @if(Request()->query("company_source") == "company" && Request()->query("company_search"))
                                            <div class="reset-search js-ak-reset-search">
                                                @includeIf("layouts.icons.reset_search_icon")
                                            </div>
                                        @endif
                                    </div>
                                </form>
                            </div>
                            @can('add-Company')
                            <div class="right">
                                <a href="{{route('company.create')}}" class="button primary-button add-new"
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
                                   <th>Company Name</th>   <th>Company Code</th>   <th>Legal Entity Type</th>   <th>Registration Number</th>   <th>GST Number</th>   <th>TIN Number</th>   <th>Logo</th>   <th>Website</th>   <th>Email</th>
                                <th class="no-sort manage-th" data-orderable="false">
                                    <div class="manage-links">

                                    </div>
                                </th>
                            </tr>
                            </thead>
                            <tbody class="">
                                @foreach($company_list as $data)
                                   <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{ $data->company_name }}</td>
<td>{{ $data->company_code }}</td>
<td>{{ $data->legal_entity_type }}</td>
<td>{{ $data->registration_number }}</td>
<td>{{ $data->gst_number }}</td>
<td>{{ $data->tin_number }}</td>
<td class="image-col">
@if ($data->logo)
<a href="{{ $data->logo }}" target="_blank" class="item-image lightbox">
<div style="background-image: url('{{ $data->logo }}')"></div>
</a>
@endif
</td>
<td>{{ $data->website }}</td>
<td>{{ $data->email }}</td>

                                         <td class="manage-td">
                                            <div class="manage-links">
                                            @can('edit-Company')
                                                <a href="{{route('company.edit',$data->id)}}" class="edit-link"
                                                   draggable="false">@includeIf("layouts.icons.edit_icon")</a>
                                            @endcan
                                            @can('delete-Company')
                                                <a data-link="{{route('company.destroy',$data->id)}}"
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
