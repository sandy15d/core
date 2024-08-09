@extends('layouts.app')
@push('breadcrumb')
    <li class="breadcrumb-item active">Mapping Builder</li>
@endpush
@push('page-title')

@endpush

@section('content')
    <div class="page-content-width-full">
        <div
            class="content-layout content-width-full mapping-data-content js-ak-DataTable js-ak-delete-container js-ak-content-layout"
            data-id="mapping"
            data-delete-modal-action="">
            <div class="content-element">
                <div class="content-header">
                    <div class="header">
                        <h3>Mapping List</h3>
                    </div>
                    <div class="action">
                        <div class="left">
                            <form class="search-container">
                                <input name="mapping_source" value="mapping" type="hidden"/>
                                <input name="mapping_length" value="{{Request()->query("mapping_length")}}" type="hidden"/>
                                <div class="search">
                                    <input type="text" autocomplete="off" placeholder="Search" name="mapping_search"
                                           value="{{(Request()->query("mapping_source") == "mapping")?Request()->query("mapping_search")??"":""}}"
                                           class="form-input js-ak-search-input">
                                    <button class="search-button" draggable="false">
                                        @includeIf("layouts.icons.search_icon")
                                    </button>
                                    @if(Request()->query("mapping_source") == "mapping" && Request()->query("mapping_search"))
                                        <div class="reset-search js-ak-reset-search">
                                            @includeIf("layouts.icons.reset_search_icon")
                                        </div>
                                    @endif
                                </div>
                            </form>
                        </div>
                        <div class="right">
                            <a href="{{route('mapping-builder.create')}}" class="button primary-button add-new"
                               draggable="false">
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
                            <th>Mapping Name</th>
                            <th>Parent</th>
                            <th>Child</th>
                            <th>Relationship Type</th>
                            <th class="no-sort manage-th" data-orderable="false">

                            </th>
                        </tr>
                        </thead>
                        <tbody class="">
                        @forelse($list as $data)
                            <tr>
                                <td>
                                    {{$data->id}}
                                </td>
                                <td>
                                    {{$data->mapping_name}}
                                </td>
                                <td>
                                    {{$data->parent}}
                                </td>
                                <td>
                                    {{$data->child}}
                                </td>
                                <td>
                                    {{$data->relationship_type}}
                                </td>

                                <td class="manage-td">

                                        <div class="manage-links">
                                            <a href="{{route("mapping-builder.edit", $data->id)}}" class="edit-link"
                                               draggable="false">@includeIf("layouts.icons.edit_icon")</a>
                                            <a data-link="{{route('mapping-builder.destroy',$data->id)}}"
                                               href="javascript:void(0);" data-id="{{$data->id}}"
                                               class="delete-link js-ak-delete-link"
                                               draggable="false">@includeIf("layouts.icons.delete_icon")</a>
                                            <a href="{{route("mapping-builder.generate_page",['page'=> base64_encode($data->id)])}}"
                                               class="edit-link" title="Generate Mapping"
                                               draggable="false">@includeIf("layouts.icons.page_generate")</a>
                                        </div>

                                </td>

                            </tr>
                        @empty
                        @endforelse
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
