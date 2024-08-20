@extends('layouts.app')
@push('breadcrumb')
    <li class="breadcrumb-item">API Builder</li>
    <li class="breadcrumb-item active">API</li>
@endpush
@push('bottom_style')
    <link rel="stylesheet" href="{{URL::to('/')}}/assets/css/bootstrap-grid.min.css">
@endpush
@section('content')
    <div class="form-container content-width-full mapping-form-content js-ak-delete-container">
        <form method="POST"
              action="@isset($data->id){{route("api-builder.update", $data->id)}}@else{{route("api-builder.store")}}@endisset"
              enctype="multipart/form-data" class="form-page validate-form" novalidate>
            <div hidden>
                @isset($data->id)
                    @method('PUT')
                @endisset
                @csrf
            </div>
            <div class="form-header">
                <h3>{{ isset($data->id) ? 'Update' : 'Add New' }}</h3>
                <p class="danger-text"><b>Note: Do not use any reserved words,special characters or hyphens (-) in the api
                        name.
                        Instead you can use underscores (_).</b></p>
                <div class="form-delete-record">
                    @if(isset($data->id))
                        <a href="#" data-link="{{route('api-builder.destroy',$data->id)}}" data-id="{{$data->id}}"
                           class="delete-link js-ak-delete-link" draggable="false">
                            @includeIf("layouts.icons.delete_icon")
                        </a>
                    @endIf
                </div>
            </div>
            @includeIf("layouts.errors")
            <div class="form-content">
                <div class="row-25 el-box-text">
                    <div class="input-container">
                        <div class="input-label">
                            <label for="model">Data From</label>
                        </div>
                        <div class="input-data">
                            <select class="form-select js-ak-select2" id="model" name="model">
                                <option value="">Select Model</option>
                                @foreach($table_list as $key=>$value)
                                    <option
                                        value="{{$key}}" {{ $data->model == $key ? "selected" : "" }}>{{$key}}</option>
                                @endforeach
                            </select>
                            <div class="error-message @if ($errors->has('model')) show @endif">Required!</div>
                            <div class="text-muted" id="model_help"></div>
                        </div>
                    </div>
                </div>
                <div class="row-25 el-box-text">
                    <div class="input-container">
                        <div class="input-label">
                            <label for="api_name">API Name</label>
                        </div>
                        <div class="input-data">
                            <input type="text" class="form-input" id="api_name" autocomplete="off"
                                   name="api_name" placeholder="API Name"
                                   value="{{{ old('api_name', $data->api_name??'') }}}">
                            <div class="error-message @if ($errors->has('api_name')) show @endif">Required!</div>
                            <div class="text-muted" id="api_name_help"></div>
                        </div>
                    </div>
                </div>
                <div class="row-25 el-box-text">
                    <div class="input-container">
                        <div class="input-label">
                            <label for="route_name">API End Point</label>
                        </div>
                        <div class="input-data">
                            <input type="text" class="form-input" id="route_name" autocomplete="off"
                                   name="route_name" placeholder="API End Point"
                                   value="{{{ old('route_name', $data->route_name??'') }}}" readonly>
                            <div class="error-message @if ($errors->has('route_name')) show @endif">Required!</div>
                            <div class="text-muted" id="route_name_help"></div>
                        </div>
                    </div>
                </div>

                <div class="row-25 el-box-text">
                    <div class="input-container">
                        <div class="input-label">
                            <label for="method_name">Controller Method</label>
                        </div>
                        <div class="input-data">
                            <input type="text" class="form-input" id="method_name" autocomplete="off"
                                   name="method_name" placeholder="API End Point"
                                   value="{{{ old('method_name', $data->method_name??'') }}}" readonly>
                            <div class="error-message @if ($errors->has('method_name')) show @endif">Required!</div>
                            <div class="text-muted" id="method_name_help"></div>
                        </div>
                    </div>
                </div>

                <div class="row-50 el-box-text">
                    <div class="input-container">
                        <div class="input-label">
                            <label for="parameters">Parameters (comma-separated)</label>
                        </div>
                        <div class="input-data">
                            <input type="text" class="form-input" id="parameters" autocomplete="off"
                                   name="parameters" placeholder="parameters"
                                   value="{{{ old('parameters', $data->parameters??'') }}}">
                        </div>
                    </div>
                </div>
               <div class="row-50">
                   <!-- Predefined Conditions -->
                   <div class="input-container">
                       <div class="input-label">
                           <label for="predefined_condition">Predefined Conditions</label>
                       </div>
                       <div id="predefined_conditions_wrapper" class="input-data">
                           @if(isset($predefinedConditions) && is_array($predefinedConditions))
                               @foreach($predefinedConditions as $index => $condition)
                                   <div class="predefined_condition row">
                                       <div class="row-25 col">
                                           <input type="text" name="predefined_conditions[{{ $index }}][field]"
                                                  value="{{ $condition['field'] ?? '' }}" placeholder="Field"
                                                  class="form-input">
                                       </div>

                                       <div class="col">
                                           <select name="predefined_conditions[{{ $index }}][operator]"
                                                   class="form-select">
                                               <option value="">Select</option>
                                               @foreach($operators as $value => $label)
                                                   <option
                                                       value="{{ $value }}" {{ isset($condition['operator']) && $condition['operator'] == $value ? 'selected' : '' }}> {{ $label }}  </option>
                                               @endforeach
                                           </select>
                                       </div>
                                       <div class="row-25 col">
                                           <input type="text" name="predefined_conditions[{{ $index }}][value]"
                                                  value="{{ $condition['value'] ?? '' }}" placeholder="Value"
                                                  class="form-input">
                                       </div>
                                       <div class="col">
                                           <div class="row">
                                               <div class="col-sm-2"><span class="remove_condition_button" role="button"
                                                                           tabindex="0">X</span>
                                               </div>
                                               <div class="col-sm-2"><span id="add_condition_button"
                                                                           role="button">@includeIf("layouts.icons.add_new_icon")</span>
                                               </div>
                                           </div>
                                       </div>
                                   </div>

                               @endforeach
                           @else
                               <div class="predefined_condition row">
                                   <div class="row-25 col">
                                       <input type="text" name="predefined_conditions[0][field]" placeholder="Field"
                                              class="form-input">
                                   </div>
                                   <div class="col">
                                       <select name="predefined_conditions[0][operator]" class="form-select">
                                           <option value="">Select</option>
                                           @foreach($operators as $value => $label)
                                               <option value="{{ $value }}"> {{ $label }} </option>
                                           @endforeach
                                       </select>
                                   </div>
                                   <div class="row-25 col">
                                       <input type="text" name="predefined_conditions[0][value]" placeholder="Value"
                                              class="form-input">
                                   </div>
                                   <div class="col">
                                       <button type="button" id="add_condition_button"
                                               class="button">@includeIf("layouts.icons.add_new_icon")</button>
                                   </div>
                               </div>
                           @endif
                       </div>

                       @error('predefined_conditions')
                       <div class="alert alert-danger">{{ $message }}</div>
                       @enderror
                   </div>
               </div>

                <div class="row-100">
                    <div class="input-container">
                        <div class="input-label">
                            <label for="description">Description</label>
                        </div>
                        <div class="input-data">
                            <textarea id="description" name="description"
                                      class="form-input">{{{ old('description', $data->description??'') }}}</textarea>
                        </div>
                    </div>
                </div>

            </div>
            @includeIf("layouts.form_footer",["cancel_route"=>route("api-builder.index")])
        </form>
    </div>
@endsection
@push('bottom_script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let conditionIndex = document.querySelectorAll('#predefined_conditions_wrapper .predefined_condition').length;

            // Add new condition
            document.getElementById('add_condition_button')?.addEventListener('click', function () {
                const wrapper = document.getElementById('predefined_conditions_wrapper');

                const conditionDiv = document.createElement('div');
                conditionDiv.className = 'predefined_condition row';
                conditionDiv.innerHTML = `
            <div class="row-25 col">
                <input type="text" name="predefined_conditions[${conditionIndex}][field]" placeholder="Field" class="form-input">
            </div>
            <div class="col">
               <select name="predefined_conditions[${conditionIndex}][operator]" class="form-select">
                <option value="">Select</option>
                    @foreach($operators as $value => $label)
                <option value="{{ $value }}"> {{ $label }} </option>
                    @endforeach
                </select>
            </div>
            <div class="row-25 col">
                <input type="text" name="predefined_conditions[${conditionIndex}][value]" placeholder="Value" class="form-input">
            </div>
            <div class="col">
                <span class="remove_condition_button" role="button" tabindex="0">X</span>
            </div>
        `;

                wrapper.appendChild(conditionDiv);
                conditionIndex++;
            });

            // Remove condition
            document.getElementById('predefined_conditions_wrapper').addEventListener('click', function (event) {
                if (event.target.classList.contains('remove_condition_button')) {
                    const conditionDiv = event.target.closest('.predefined_condition');
                    if (conditionDiv) {
                        conditionDiv.remove();
                    }
                }
            });
        });
        $(document).ready(function () {
            $("#api_name").on('input', function () {
                const apiName = $(this).val().trim();
                $("#route_name").val(generateApiEndpoint(apiName));
                $("#method_name").val(generateControllerMethod(apiName));
            });

            function generateApiEndpoint(apiName) {
                return apiName.replace(/\s+/g, '_').toLowerCase();
            }

            function generateControllerMethod(apiName) {
                return apiName
                    .toLowerCase()
                    .replace(/\s+(.)/g, (_, group1) => group1.toUpperCase());
            }
        });

    </script>
@endpush
