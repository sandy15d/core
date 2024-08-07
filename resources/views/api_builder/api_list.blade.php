@extends('layouts.app')
@push('breadcrumb')
    <li class="breadcrumb-item active">API Builder</li>
@endpush


@section('content')
    <div class="page-content-width-full">
        <div class="content-layout content-width-full api-data-content js-ak-DataTable js-ak-delete-container js-ak-content-layout">
            <div class="content-element">
                <div class="content-header">
                    <div class="action">
                        <div class="left">
                            <form class="search-container">
                                <input name="country_source" value="country" type="hidden"/>
                                <input name="country_length" value="{{Request()->query("country_length")}}"
                                       type="hidden"/>
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
                        <div class="right">
                            <a href="{{route('api-builder.create')}}" class="button primary-button add-new"
                               draggable="false" data-bs-toggle="modal" data-bs-target="#menuModal">
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
                            <th>API End Point</th>
                            <th>Model</th>
                            <th>Parameters</th>
                            <th>Pre Defined Conditions</th>
                            <th class="no-sort manage-th" data-orderable="false">
                                <div class="manage-links">

                                </div>
                            </th>
                        </tr>
                        </thead>
                        <tbody class="">
                        @foreach($api_list as $data)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$data->route_name}}</td>
                                <td>{{$data->model}}</td>
                                <td>{{$data->parameters}}</td>
                                <td>
                                    @if(is_array($data->predefined_conditions))
                                        @foreach($data->predefined_conditions as $condition)
                                            {{ $condition['field'] ?? '' }} {{ $condition['operator'] ?? '' }} {{ $condition['value'] ?? '' }}
                                            <br>
                                        @endforeach
                                    @else
                                        {{ $data->predefined_conditions }}
                                    @endif
                                </td>
                                <td class="manage-td">
                                    <div class="manage-links">
                                        <a href="{{route('api-builder.edit',$data->id)}}" class="edit-link"
                                           draggable="false">@includeIf("layouts.icons.edit_icon")</a>
                                        <a data-link="{{route('api-builder.destroy',$data->id)}}"
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
    </div>
@endsection
