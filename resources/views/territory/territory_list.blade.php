@extends('layouts.app')
@push('breadcrumb')
    <li class="breadcrumb-item active">Territory</li>
@endpush

@section('content')
     <div class="page-content-width-full">
            <div
                class="content-layout content-width-full territory-data-content js-ak-DataTable js-ak-delete-container js-ak-content-layout"
                data-id="territory"
                data-delete-modal-action="">
                <div class="content-element">
                    <div class="content-header">
                        <div class="header">
                            <h3>Territory List</h3>
                        </div>
                        <div class="action">
                            <div class="left">
                                <form class="search-container">
                                    <input name="territory_source" value="territory" type="hidden"/>
                                    <input name="territory_length" value="{{Request()->query("territory_length")}}" type="hidden"/>
                                    <div class="search">
                                        <input type="text" autocomplete="off" placeholder="Search" name="territory_search"
                                               value="{{(Request()->query("territory_source") == "territory")?Request()->query("territory_search")??"":""}}"
                                               class="form-input js-ak-search-input">
                                        <button class="search-button" draggable="false">
                                            @includeIf("layouts.icons.search_icon")
                                        </button>
                                        @if(Request()->query("territory_source") == "territory" && Request()->query("territory_search"))
                                            <div class="reset-search js-ak-reset-search">
                                                @includeIf("layouts.icons.reset_search_icon")
                                            </div>
                                        @endif
                                    </div>
                                </form>
                            </div>
                            @can('add-Territory')
                            <div class="right">
                                <a href="{{route('territory.create')}}" class="button primary-button add-new"
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
                                   <th>Territory Name</th>   <th>Territory Code</th>   <th>Numeric Code</th>   <th>Effective Date</th>   <th>Is Active</th>
                                <th class="no-sort manage-th" data-orderable="false">
                                    <div class="manage-links">

                                    </div>
                                </th>
                            </tr>
                            </thead>
                            <tbody class="">
                                @foreach($territory_list as $data)
                                   <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{ $data->territory_name }}</td>
<td>{{ $data->territory_code }}</td>
<td>{{ $data->numeric_code }}</td>
<td>{{ $data->effective_date }}</td>
<td>{{ $data->is_active }}</td>

                                         <td class="manage-td">
                                            <div class="manage-links">
                                            @can('edit-Territory')
                                                <a href="{{route('territory.edit',$data->id)}}" class="edit-link"
                                                   draggable="false">@includeIf("layouts.icons.edit_icon")</a>
                                            @endcan
                                            @can('delete-Territory')
                                                <a data-link="{{route('territory.destroy',$data->id)}}"
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
