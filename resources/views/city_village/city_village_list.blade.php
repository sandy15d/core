@extends('layouts.app')
@push('breadcrumb')
    <li class="breadcrumb-item active">City Village</li>
@endpush

@section('content')
     <div class="page-content-width-full">
            <div
                class="content-layout content-width-full city_village-data-content js-ak-DataTable js-ak-delete-container js-ak-content-layout"
                data-id="city_village"
                data-delete-modal-action="">
                <div class="content-element">
                    <div class="content-header">
                        <div class="header">
                            <h3>City Village List</h3>
                        </div>
                        <div class="action">
                            <div class="left">
                                <form class="search-container">
                                    <input name="city_village_source" value="city_village" type="hidden"/>
                                    <input name="city_village_length" value="{{Request()->query("city_village_length")}}" type="hidden"/>
                                    <div class="search">
                                        <input type="text" autocomplete="off" placeholder="Search" name="city_village_search"
                                               value="{{(Request()->query("city_village_source") == "city_village")?Request()->query("city_village_search")??"":""}}"
                                               class="form-input js-ak-search-input">
                                        <button class="search-button" draggable="false">
                                            @includeIf("layouts.icons.search_icon")
                                        </button>
                                        @if(Request()->query("city_village_source") == "city_village" && Request()->query("city_village_search"))
                                            <div class="reset-search js-ak-reset-search">
                                                @includeIf("layouts.icons.reset_search_icon")
                                            </div>
                                        @endif
                                    </div>
                                </form>
                            </div>
                            @can('add-CityVillage')
                            <div class="right">
                                <a href="{{route('city_village.create')}}" class="button primary-button add-new"
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
                                   <th>State</th>   <th>District</th>   <th>Division Name</th>   <th>City/Village Name</th>   <th>Pincode</th>   <th>Longitude</th>   <th>Latitude</th>   <th>Effective Date</th>   <th>Is Active</th>
                                <th class="no-sort manage-th" data-orderable="false">
                                    <div class="manage-links">

                                    </div>
                                </th>
                            </tr>
                            </thead>
                            <tbody class="">
                                @foreach($city_village_list as $data)
                                   <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{ $data->state->state_name ?? "" }}</td>
<td>{{ $data->district->district_name ?? "" }}</td>
<td>{{ $data->division_name }}</td>
<td>{{ $data->city_village_name }}</td>
<td>{{ $data->pincode }}</td>
<td>{{ $data->longitude }}</td>
<td>{{ $data->latitude }}</td>
<td>{{ $data->effective_date }}</td>
<td>{{ $data->is_active }}</td>

                                         <td class="manage-td">
                                            <div class="manage-links">
                                            @can('edit-CityVillage')
                                                <a href="{{route('city_village.edit',$data->id)}}" class="edit-link"
                                                   draggable="false">@includeIf("layouts.icons.edit_icon")</a>
                                            @endcan
                                            @can('delete-CityVillage')
                                                <a data-link="{{route('city_village.destroy',$data->id)}}"
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
