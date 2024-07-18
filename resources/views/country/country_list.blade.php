@extends('layouts.app')
@push('breadcrumb')
    <li class="breadcrumb-item active">Country</li>
@endpush
@push('page-title')
Country
@endpush
@section('content')
     <div class="page-content-width-full">
            <div
                class="content-layout content-width-full country-data-content js-ak-DataTable js-ak-delete-container js-ak-content-layout"
                data-id="country"
                data-delete-modal-action="">
                <div class="content-element">
                    <div class="content-header">
                        <div class="header">
                            <h3>Country List</h3>
                        </div>
                        <div class="action">
                            <div class="left">
                                <form class="search-container">
                                    <input name="country_source" value="country" type="hidden"/>
                                    <input name="country_length" value="{{Request()->query("country_length")}}" type="hidden"/>
                                    <div class="search">
                                        <input type="text" autocomplete="off" placeholder="Search" name="country_search"
                                               value="{{(Request()->query("country_source") == "country")?Request()->query("country_search")??"":""}}"
                                               class="form-input js-ak-search-input">
                                        <button class="search-button" draggable="false">
                                            @includeIf("layouts.icons.search_icon")
                                        </button>
                                        @if(Request()->query("country_source") == "country" && Request()->query("country_search"))
                                            <div class="reset-search js-ak-reset-search">
                                                @includeIf("layouts.icons.reset_search_icon")
                                            </div>
                                        @endif
                                    </div>
                                </form>
                            </div>
                            @can('add-Country')
                            <div class="right">
                                <a href="{{route('country.create')}}" class="button primary-button add-new"
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
                                   <th>Country Name</th>   <th>Country Code</th>   <th>Is Active</th>
                                <th class="no-sort manage-th" data-orderable="false">
                                    <div class="manage-links">

                                    </div>
                                </th>
                            </tr>
                            </thead>
                            <tbody class="">
                                @foreach($country_list as $data)
                                   <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{ $data->country_name }}</td>
<td>{{ $data->country_code }}</td>
<td>{{ $data->is_active }}</td>

                                         <td class="manage-td">
                                            <div class="manage-links">
                                            @can('edit-Country')
                                                <a href="{{route('country.edit',$data->id)}}" class="edit-link"
                                                   draggable="false">@includeIf("layouts.icons.edit_icon")</a>
                                            @endcan
                                            @can('delete-Country')
                                                <a data-link="{{route('country.destroy',$data->id)}}"
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
