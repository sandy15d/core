@extends('layouts.app')
@push('breadcrumb')
    <li class="breadcrumb-item active">Zone Region Mapping</li>
@endpush
@push('bottom_style')
    <link rel="stylesheet" href="{{URL::to('/')}}/assets/css/bootstrap-grid.min.css">
@endpush
@section('content')
    <div class="page-content-width-full">
        <div class="content-layout content-width-full js-ak-DataTable">
            <div class="content-element">
                <div class="content-header">
                    <div class="action">
                        <div class="left">
                            <form class="search-container">
                                <input name="Zone_source" value="Zone" type="hidden"/>
                                <input name="Zone_length" value="{{Request()->query("Zone_length")}}"
                                       type="hidden"/>
                                <div class="search">
                                    <input type="text" autocomplete="off" placeholder="Search" name="Zone_search"
                                           value="{{(Request()->query("Zone_source") == "Zone")?Request()->query("Zone_search")??"":""}}"
                                           class="form-input js-ak-search-input">
                                    <button class="search-button" draggable="false">
                                        @includeIf("layouts.icons.search_icon")
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="right">
                            <a href="{{route('zone_region_mappings_list')}}">Mapped List</a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-1">
                        <span style="font-weight: bold;margin-left: 25px;">↱</span>
                        <label class="text-primary">
                            <input id="check_all" type="checkbox" name="" style="display: inline-block!important;"
                                   class="form-checkbox">
                        </label>
                    </div>
                    <div class="col-md-2">
                        <select name="zone_id" id="zone_id" class="form-select">
                            <option value="">Select Zone</option>
                            @foreach($Zone_list as $key=>$value)
                                <option value="{{$key}}">{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="effective_from" autocomplete="off" id="effective_from"
                               class="form-input js-ak-date-picker" placeholder="Effective Date">
                    </div>
                    <div class="col-md-1">
                        <button class="button primary-button" id="map_btn">Map</button>
                    </div>
                </div>
                <div class="content table-content">
                    <table class="table js-ak-content">
                        <thead>
                        <tr data-sort-method='thead'>
                            <th>#</th>
                            <th class="table-id" data-sort-method="number">S.No</th>
                            <th>Region Name</th><th>Region Code</th><th>Vertical</th>
                        </tr>
                        </thead>
                        <tbody class="">
                            @foreach($Region_list as $data)
                                <tr>
                                    <td><input type="checkbox" class="form-checkbox check" onclick="checkAllOrNot()" value="{{$data->id}}" name="region_select"></td>
                                     <td>{{$loop->iteration}}</td>
                                     <td>{{ $data->region_name }}</td>
<td>{{ $data->region_code }}</td>
<td>{{ $data->vertical->vertical_name ?? "" }}</td>

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

@push('bottom_script')
    <script>
        $('#check_all').click(function () {
            if ($(this).prop("checked") === true) {
                $('.check').prop("checked", true);
            } else if ($(this).prop("checked") === false) {
                $('.check').prop("checked", false);
            }
        });
        function checkAllOrNot() {
            var all_chk = 1;
            $('.check').each(function () {
                if ($(this).prop("checked") == false) {
                    all_chk = 0;
                }
            });
            if (all_chk == 0) {
                $('#check_all').prop("checked", false);
            } else if (all_chk == 1) {
                $('#check_all').prop("checked", true);
            }
        }

        $(document).on('click','#map_btn',function(){
            var region_ids = [];
            var zone_id = $("#zone_id").val();
            var effective_from = $("#effective_from").val();
            $("input[name='region_select']").each(function(){
                if($(this).prop('checked')=== true){
                    var value = $(this).val();
                   region_ids.push(value);
                }
            });
            console.log(region_ids);
            if(region_ids.length > 0){
                if(confirm('Are you sure to map selected Region to Zone?')){
                    $.ajax({
                        url: "{{url('zone_region_mappings_data')}}",
                        method: 'POST',
                        data:{
                            region_ids : region_ids,
                             zone_id:zone_id,
                             effective_from:effective_from
                        },
                        headers: {
                           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success:function(response){
                            if(response.status === 400){
                                ToastStart.error('Something went wrong.. Please try again');
                            }else{
                                ToastStart.success('Region Mapped Successfully to Zone.');
                            }
                            setTimeout(function () {
                                location.reload(true);
                            }, 2000);
                        }
                    });
                }else{
                   window.location.reload();
                }
            }else{
                ToastStart.error('No Region Selected!\nPlease select at least one Region to proceed.');
            }
        });
    </script>
@endpush
