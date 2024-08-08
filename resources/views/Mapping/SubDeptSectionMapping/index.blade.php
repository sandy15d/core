@extends('layouts.app')
@push('breadcrumb')
    <li class="breadcrumb-item active">Sub Dept Section Mapping</li>
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
                                <input name="SubDepartment_source" value="SubDepartment" type="hidden"/>
                                <input name="SubDepartment_length" value="{{Request()->query("SubDepartment_length")}}"
                                       type="hidden"/>
                                <div class="search">
                                    <input type="text" autocomplete="off" placeholder="Search" name="SubDepartment_search"
                                           value="{{(Request()->query("SubDepartment_source") == "SubDepartment")?Request()->query("SubDepartment_search")??"":""}}"
                                           class="form-input js-ak-search-input">
                                    <button class="search-button" draggable="false">
                                        @includeIf("layouts.icons.search_icon")
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="right">
                            <a href="{{route('sub_dept_section_mappings_list')}}">Mapped List</a>
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
                        <select name="sub_department_id" id="sub_department_id" class="form-select">
                            <option value="">Select SubDepartment</option>
                            @foreach($SubDepartment_list as $key=>$value)
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
                            <th>Section Name</th><th>Section Code</th><th>Numeric Code</th>
                        </tr>
                        </thead>
                        <tbody class="">
                            @foreach($Section_list as $data)
                                <tr>
                                    <td><input type="checkbox" class="form-checkbox check" onclick="checkAllOrNot()" value="{{$data->id}}" name="section_select"></td>
                                     <td>{{$loop->iteration}}</td>
                                     <td>{{ $data->section_name }}</td>
<td>{{ $data->section_code }}</td>
<td>{{ $data->numeric_code }}</td>

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
            var section_ids = [];
            var sub_department_id = $("#sub_department_id").val();
            var effective_from = $("#effective_from").val();
            $("input[name='section_select']").each(function(){
                if($(this).prop('checked')=== true){
                    var value = $(this).val();
                   section_ids.push(value);
                }
            });
            console.log(section_ids);
            if(section_ids.length > 0){
                if(confirm('Are you sure to map selected Section to SubDepartment?')){
                    $.ajax({
                        url: "{{url('sub_dept_section_mappings_data')}}",
                        method: 'POST',
                        data:{
                            section_ids : section_ids,
                             sub_department_id:sub_department_id,
                             effective_from:effective_from
                        },
                        headers: {
                           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success:function(response){
                            if(response.status === 400){
                                ToastStart.error('Something went wrong.. Please try again');
                            }else{
                                ToastStart.success('Section Mapped Successfully to SubDepartment.');
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
                ToastStart.error('No Section Selected!\nPlease select at least one Section to proceed.');
            }
        });
    </script>
@endpush
