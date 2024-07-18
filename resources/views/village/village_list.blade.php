@extends('layouts.app')
@push('breadcrumb')
    <li class="breadcrumb-item active">Village</li>
@endpush
@push('page-title')
Village
@endpush
@section('content')
     <div class="page-content-width-full">
            <div
                class="content-layout content-width-full page-data-content js-ak-DataTable js-ak-delete-container js-ak-content-layout"
                data-id="page"
                data-delete-modal-action="">
                <div class="content-element">
                    <div class="content-header">
                        <div class="header">
                            <h3>Village List</h3>
                        </div>
                        <div class="action">
                            <div class="left">
                                <form class="search-container">
                                    <input name="page_source" value="page" type="hidden"/>
                                    <input name="page_length" value="{{Request()->query("page_length")}}" type="hidden"/>
                                    <div class="search">
                                        <input type="text" autocomplete="off" placeholder="Search" name="page_search"
                                               value="{{(Request()->query("page_source") == "page")?Request()->query("page_search")??"":""}}"
                                               class="form-input js-ak-search-input">
                                        <button class="search-button" draggable="false">
                                            @includeIf("layouts.icons.search_icon")
                                        </button>
                                        @if(Request()->query("page_source") == "page" && Request()->query("page_search"))
                                            <div class="reset-search js-ak-reset-search">
                                                @includeIf("layouts.icons.reset_search_icon")
                                            </div>
                                        @endif
                                    </div>
                                </form>
                            </div>
                            <div class="right">
                                <a href="{{route('village.create')}}" class="button primary-button add-new"
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
                                <th class="no-sort manage-th" data-orderable="false">
                                    <div class="manage-links">

                                    </div>
                                </th>
                            </tr>
                            </thead>
                            <tbody class="">
                               <tr>
                                    <td>1</td>
                                     <td class="manage-td">
                                        <div class="manage-links">
                                            <a href="javascript:void(0)" class="edit-link"
                                               draggable="false">@includeIf("layouts.icons.edit_icon")</a>
                                            <a data-link="javascript:void(0);"
                                               href="javascript:void(0);"
                                               class="delete-link js-ak-delete-link"
                                               draggable="false">@includeIf("layouts.icons.delete_icon")</a>
                                        </div>
                                    </td>
                               </tr>
                               <tr>
                                   <td>2</td>
                                    <td class="manage-td">
                                       <div class="manage-links">
                                           <a href="javascript:void(0)" class="edit-link"
                                              draggable="false">@includeIf("layouts.icons.edit_icon")</a>
                                           <a data-link="javascript:void(0);"
                                              href="javascript:void(0);"
                                              class="delete-link js-ak-delete-link"
                                              draggable="false">@includeIf("layouts.icons.delete_icon")</a>
                                       </div>
                                   </td>
                              </tr>
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
