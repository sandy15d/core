@extends('layouts.app')
@push('breadcrumb')
    <li class="breadcrumb-item active">Function Vertical Mapping List</li>
@endpush
@push('page-back-button')
    <div class="page-back-button">
        <a href="{{ route('function_vertical_mappings.index')}}">
            <div class="icon">
                <div class="font-awesome-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512">
                        <path d="M192 448c-8.188 0-16.38-3.125-22.62-9.375l-160-160c-12.5-12.5-12.5-32.75 0-45.25l160-160c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25L77.25 256l137.4 137.4c12.5 12.5 12.5 32.75 0 45.25C208.4 444.9 200.2 448 192 448z"/>
                    </svg>
                </div>
            </div>
            <div>Back</div>
        </a>
    </div>
@endpush
@section('content')
    <div class="page-content-width-full">
        <div class="content-layout content-width-full js-ak-DataTable">
            <div class="content-element">
                <div class="content-header">
                    <div class="action">
                        <div class="left">
                            <form class="search-container">
                                <input name="mapping_source" value="mapping" type="hidden"/>
                                <input name="mapping_length" value="{{Request()->query("mapping_length")}}"
                                       type="hidden"/>
                                <div class="search">
                                    <input type="text" autocomplete="off" placeholder="Search" name="mapping_search"
                                           value="{{(Request()->query("mapping_source") == "mapping")?Request()->query("mapping_search")??"":""}}"
                                           class="form-input js-ak-search-input">
                                    <button class="search-button" draggable="false">
                                        @includeIf("layouts.icons.search_icon")
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="content table-content">
                    <table class="table js-ak-content">
                        <thead>
                        <tr data-sort-method='thead'>
                            <th class="table-id" data-sort-method="number">S.No</th>
                            <th>Org Function</th>
<th>Vertical</th>
<th>Effective From</th>
<th>Effective To</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($list as $data)
                                <tr>
                                     <td>{{$loop->iteration}}</td>
                                     <td>{{ $data->orgFunction->function_name }}</td>
<td>{{ $data->vertical->vertical_name }}</td>
<td>{{ $data->effective_from }}</td>
<td>{{ $data->effective_to }}</td>
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
    </div>
@endsection

