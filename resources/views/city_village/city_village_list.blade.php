@extends('layouts.app')
@push('breadcrumb')
    <li class="breadcrumb-item active">City Village</li>
@endpush
@push('upper_style')

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
          crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous">
    </script>
    <style>
        /* In your CSS file or within a <style> tag */
        .pagination .page-link {
            color: black;
        }

        .pagination .page-item.active .page-link {
            background-color: rgb(165 133 163);
            border-color: rgb(165 133 163);
            color: white; /* Adjust this as needed */
        }

    </style>
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

                                <div class="search">
                                    <input type="text" autocomplete="off" placeholder="Search"
                                           name="search" id="search" class="form-input js-ak-search-input">
                                    <button class="search-button" draggable="false" type="button"
                                            onkeyup="getVillageList();">
                                        @includeIf("layouts.icons.search_icon")
                                    </button>
                                    @if (isset($_REQUEST['search']) && $_REQUEST['search'] != '')
                                        <script>
                                            $('#search').val('<?= $_REQUEST['search'] ?>');
                                        </script>
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
                    <table class="table">
                        <thead>
                        <tr data-sort-method='thead'>
                            <th class="table-id" data-sort-method="number">ID</th>
                            <th>State</th>
                            <th>District</th>
                            <th>Division Name</th>
                            <th>City/Village Name</th>
                            <th>Pincode</th>
                            <th>Longitude</th>
                            <th>Latitude</th>
                            <th>Effective Date</th>
                            <th>Is Active</th>
                            <th class="no-sort manage-th" data-orderable="false">
                                <div class="manage-links">

                                </div>
                            </th>
                        </tr>
                        </thead>
                        <tbody class="">
                        @foreach($city_village_list as $data)
                            <tr>
                                <td>{{$city_village_list->firstItem() + $loop->index}}</td>
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
                    {{$city_village_list->appends([])->links()}}
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
@push('bottom_script')
    <script>
        function getVillageList() {
            var search = $('#search').val() || '';
            window.location.href = "{{ route('city_village.index') }}?search=" + search;
        }

    </script>
@endpush
