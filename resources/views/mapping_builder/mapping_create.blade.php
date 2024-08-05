@extends('layouts.app')
@push('breadcrumb')
    <li class="breadcrumb-item active">Mapping Builder</li>
@endpush
@section('content')
    <div class="form-container content-width-medium mapping-form-content js-ak-delete-container">
        <form method="POST"
              action="@isset($data->id){{route("mapping-builder.update", $data->id)}}@else{{route("mapping-builder.store")}}@endisset"
              enctype="multipart/form-data" class="form-page validate-form" novalidate>
            <div hidden>
                @isset($data->id)
                    @method('PUT')
                @endisset
                @csrf
            </div>
            <div class="form-header">
                <h3>{{ isset($data->id) ? 'Update' : 'Add New' }}</h3>
                <p class="danger-text"><b>Do not use any reserved words or special characters for names. Also, avoid using the word "Mapping".</b></p>
                <div class="form-delete-record">
                    @if(isset($data->id))
                        <a href="#" data-link="{{route('mapping-builder.destroy',$data->id)}}" data-id="{{$data->id}}"
                           class="delete-link js-ak-delete-link" draggable="false">
                            @includeIf("layouts.icons.delete_icon")
                        </a>
                    @endIf
                </div>
            </div>
            @includeIf("layouts.errors")
            <div class="form-content">
                <div class="row-33 el-box-text">
                    <div class="input-container">
                        <div class="input-label">
                            <label for="mapping_name">Mapping Name</label>
                        </div>
                        <div class="input-data">
                            <input type="text" class="form-input" id="mapping_name" autocomplete="off"
                                   name="mapping_name" placeholder="Mapping Name"
                                   value="{{{ old('mapping_name', $data->mapping_name??'') }}}">
                            <div class="error-message @if ($errors->has('mapping_name')) show @endif">Required!</div>
                            <div class="text-muted" id="mapping_name_help"></div>
                        </div>
                    </div>
                </div>
                <div class="row-33 el-box-text">
                    <div class="input-container">
                        <div class="input-label">
                            <label for="parent">Parent</label>
                        </div>
                        <div class="input-data">
                            <select class="form-select js-ak-select2" id="parent" name="parent">
                                <option value="">Select Parent Table</option>
                                @foreach($table_list as $key=>$value)
                                    <option
                                        value="{{$key}}" {{ $data->parent == $key ? "selected" : "" }}>{{$key}}</option>
                                @endforeach
                            </select>
                            <div class="error-message @if ($errors->has('parent')) show @endif">Required!</div>
                            <div class="text-muted" id="parent_help"></div>
                        </div>
                    </div>
                </div>
                <div class="row-33 el-box-text">
                    <div class="input-container">
                        <div class="input-label">
                            <label for="child">Child</label>
                        </div>
                        <div class="input-data">
                            <select class="form-select js-ak-select2" id="child" name="child">
                                <option value="">Select Child Table</option>
                                @foreach($table_list as $key=>$value)
                                    <option
                                        value="{{$key}}" {{ $data->child == $key ? "selected" : "" }}>{{$key}}</option>
                                @endforeach
                            </select>
                            <div class="error-message @if ($errors->has('child')) show @endif">Required!</div>
                            <div class="text-muted" id="child_help"></div>
                        </div>
                    </div>
                </div>
            </div>
            @includeIf("layouts.form_footer",["cancel_route"=>route("mapping-builder.index")])
        </form>
    </div>
    @isset($data->id)
        @includeIf("layouts.delete_modal_confirm")
    @endisset
@endsection

@push('bottom_script')
    <script>
        $(document).ready(function () {
            function updateSelectOptions() {
                var parentValue = $('#parent').val();
                var childValue = $('#child').val();

                $('#parent option, #child option').prop('disabled', false); // Enable all options initially

                if (parentValue) {
                    $('#child option[value="' + parentValue + '"]').prop('disabled', true);
                }

                if (childValue) {
                    $('#parent option[value="' + childValue + '"]').prop('disabled', true);
                }
            }

            $('#parent, #child').change(updateSelectOptions);

            // Initial update on page load
            updateSelectOptions();
        });
    </script>

@endpush
