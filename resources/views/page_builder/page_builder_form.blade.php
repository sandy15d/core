@extends('layouts.app')
@push('breadcrumb')
    <li class="breadcrumb-item">Form</li>
    <li class="breadcrumb-item active"> {{ $page['studly_case'] }}</li>
@endpush
@push('upper_style')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <style>
        .el-box-text {
            position: relative;
            margin-bottom: 15px;
            padding: 10px;
            border: 1px dashed transparent;
            border-radius: 13px;
            transition: border-color 0.3s;
        }

        .el-box-text:hover {
            border-color: blue;

        }

        .el-box-text .action-div {
            position: absolute;
            top: 5px;
            right: 5px;
            display: none;
        }

        .el-box-text:hover .action-div {
            display: block;
        }

        .edit-btn,
        .delete-btn {
            margin-left: 5px;
        }

        p {
            font-size: 12px;

        }

        label {
            font-size: 14px;
        }

        .add-form-element {
            --tw-border-opacity: 1;
            border-color: rgb(107 114 128 / var(--tw-border-opacity));
            border-radius: .75rem;
            border-style: dashed;
            border-width: 1px;
            display: flex;
            flex-direction: column;
            padding: .5rem;
        }

        .text-bg-dark {
            color: #fff !important;
            background-color: rgb(165 133 163) !important;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
          integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
@endpush
@section('content')
    <div class="row" style="text-align: -webkit-right;">
        <div class="col">
            <a class="btn btn-success btn-sm" id="generateForm">Generate Form</a>
            <a class="btn btn-warning btn-sm" data-bs-toggle="offcanvas" data-bs-target="#ToolBar"
               aria-controls="ToolBar">Form Builder</a>
        </div>

    </div>
    <div class="form-container content-width-full  js-ak-delete-container">
        <form method="POST" action="javascript:void(0);" enctype="multipart/form-data" class="form-page validate-form"
              novalidate>
            <div class="form-header">
                <h3>Add New:</h3>
            </div>
            <div class="form-content dynamicForm" style="margin-bottom: 40px;">
                @if ($forms_element != null)

                    @foreach ($forms_element as $input)
                        <div class="{{ $input->column_width }} el-box-text" data-id="{{ $input->id }}">
                            <div class="input-container">
                                <div class="input-label">
                                    <label for="{{ $input->column_name }}">{{ $input->column_title }}</label>
                                </div>
                                <div class="input-data">
                                    @if ($input->input_type == 'text' || $input->input_type == 'number' || $input->input_type == 'email')
                                        <input type="{{ $input->input_type }}" class="form-input"
                                               id="{{ $input->column_name }}" autocomplete="off"
                                               name="{{ $input->column_name }}" placeholder="{{ $input->placeholder }}">
                                    @elseif($input->input_type == 'select' || $input->input_type=='select2')
                                        <select id="{{ $input->column_name }}"
                                                class="form-select {{$input->input_type =='select2' ?'js-ak-select2':''}}"
                                                name="{{ $input->column_name }}">
                                            <option value="">Option 1</option>
                                            <option value="">Option 2</option>
                                        </select>
                                    @elseif($input->input_type == 'textarea')
                                        <textarea class="form-input form-textarea" id="{{ $input->column_name }}"
                                                  name="{{ $input->column_name }}"
                                                  placeholder="{{ $input->placeholder }}"></textarea>
                                    @elseif($input->input_type == 'date_time')
                                        <div class="group-input date-time-group"
                                             id="ak_date_group_{{ $input->column_name }}">
                                            <input type="text" name="{{ $input->column_name }}" autocomplete="off"
                                                   id="{{ $input->column_name }}"
                                                   class="form-input {{ $input->column_type == 'date_time' ? 'js-ak-date-time-picker' : 'js-ak-date-picker' }}"
                                                   placeholder="{{ $input->placeholder }}">
                                            <div class="input-suffix js-ak-calendar-icon"
                                                 data-target="#{{ $input->column_name }}">
                                                @includeIf('layouts.icons.calendar_icon')
                                            </div>
                                        </div>
                                    @elseif($input->input_type == 'time')
                                        <div class="group-input date-time-group"
                                             id="ak_date_group_{{ $input->column_name }}">
                                            <input type="text" name="{{ $input->column_name }}" autocomplete="off"
                                                   class="form-input js-ak-time-picker" id="{{ $input->column_name }}"
                                                   placeholder="{{ $input->placeholder }}">
                                            <div class="input-suffix js-ak-time-icon"
                                                 data-target="#{{ $input->column_name }}">
                                                @includeIf('layouts.icons.time_icon')
                                            </div>
                                        </div>
                                    @elseif($input->input_type == 'checkbox')
                                        <div class="checkbox-input {{ $input->is_switch == 'Y' ? 'form-switch' : '' }}">
                                            <input type="hidden" name="{{ $input->column_name }}" value="0">
                                            <input class="form-checkbox" type="checkbox" id="{{ $input->column_name }}"
                                                   name="{{ $input->column_name }}"
                                                   value="1">
                                            <label class="form-check-label" for="is_active"></label>
                                        </div>
                                    @elseif ($input->input_type == 'multi-checkbox')
                                        <div class="checkbox-container checkbox-inline">
                                            <label class="checkbox-input">
                                                <input type="checkbox" class="form-checkbox">
                                                <span class="form-check-label">Option 1</span>
                                            </label>
                                            <label class="checkbox-input">
                                                <input type="checkbox" class="form-checkbox">
                                                <span class="form-check-label">Option 2</span>
                                            </label>
                                        </div>
                                    @elseif($input->input_type =='image_upload')
                                        <input type="file" class="form-file js-ak-image-upload"
                                               data-id="candidate_image" accept=".jpg,.jpeg,.png,.webp"
                                               data-file-type=".jpg,.jpeg,.png,.webp" name="candidate_image"
                                               data-selected="Selected image for upload:">
                                        <div class="text-muted" id="candidate_image_help">Allowed
                                            extension:.jpg,.jpeg,.png,.webp. Recommended width 1920px, height 1080px.
                                            Image action: resize.
                                        </div>
                                    @elseif($input->input_type =='file_upload')
                                        <input type="file" class="form-file js-ak-file-upload">

                                    @endif
                                </div>
                                <div class="action-div">
                                    <button type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                                            aria-controls="offcanvasRight" data-form_id="{{ $input->id }}"
                                            onclick="getFormDetails({{ $input->id }});">@includeIf('layouts.icons.edit_icon')
                                    </button>
                                    <button class="delete-btn" type="button"
                                            data-form_id="{{ $input->id }}">@includeIf('layouts.icons.delete_icon')</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            @includeIf('layouts.form_footer', ['cancel_route' => ''])
        </form>
    </div>
    <div class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" id="ToolBar" aria-labelledby="ToolBarLabel"
         data-bs-scroll="true" data-bs-backdrop="static">
        <div class="offcanvas-header">
            <h5 id="ToolBarLabel" style="font-weight: bold;">Form Builder
            </h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close">X
            </button>
        </div>
        <div class="offcanvas-body" style="overflow-y: auto;max-height: 90vh;">
            <div class="row mt-1">
                <div class="col add-form-element">
                    <label><i class="fa-solid fa-font"></i> Title :</label>
                    <p>Short Text</p>
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-sm form-input" type="text" placeholder="Enter Name"
                               aria-label="Enter Name" aria-describedby="button-addon2" id="titleName">
                        <button class="btn btn-secondary" id="button-addon2" type="button"
                                onclick="addInput('text', 'titleName')">Add
                        </button>
                    </div>
                </div>
                <div class="col add-form-element">
                    <label><i class="fa-regular fa-file-word"></i> Content :</label>
                    <p>Long Text for description</p>
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-sm form-input" type="text" placeholder="Enter Name"
                               id="contentName" aria-label="Enter Name" aria-describedby="button-addon2">
                        <button class="btn btn-secondary" id="button-addon2" type="button"
                                onclick="addInput('textarea','contentName')">Add
                        </button>
                    </div>
                </div>
            </div>
            <div class="row mt-1">
                <div class="col add-form-element">
                    <label><i class="fa-solid fa-at"></i> Email : </label>
                    <p>Email input</p>
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-sm form-input" type="text" placeholder="Enter Name"
                               aria-label="Enter Name" aria-describedby="button-addon2" id="emailName">
                        <button class="btn btn-secondary" id="button-addon2" type="button"
                                onclick="addInput('email', 'emailName')">Add
                        </button>
                    </div>
                </div>
                <div class="col add-form-element">
                    <label><i class="fa-solid fa-list-ol"></i> Numbers : </label>
                    <p>Integer numbers</p>
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-sm form-input" type="text" placeholder="Enter Name"
                               id="numberName" aria-label="Enter Name" aria-describedby="button-addon2">
                        <button class="btn btn-secondary" id="button-addon2" type="button"
                                onclick="addInput('number','numberName')">Add
                        </button>

                    </div>
                </div>
            </div>
            <div class="row mt-1">
                <div class="col add-form-element">
                    <label> <i class="fa-solid fa-calendar-days"></i> Date & Time: </label>
                    <p>Date & Time with calendar</p>
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-sm form-input" type="text" placeholder="Enter Name"
                               aria-label="Enter Name" aria-describedby="button-addon2" id="date_time">
                        <button class="btn btn-secondary" id="button-addon2" type="button"
                                onclick="addInput('date_time', 'date_time')">Add
                        </button>
                    </div>
                </div>
                <div class="col add-form-element">
                    <label><i class="fa-regular fa-clock"></i> Time : </label>
                    <p>Time picker</p>
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-sm form-input" type="text" placeholder="Enter Name"
                               id="time" aria-label="Enter Name" aria-describedby="button-addon2">
                        <button class="btn btn-secondary" id="button-addon2" type="button"
                                onclick="addInput('time','time')">Add
                        </button>

                    </div>
                </div>
            </div>
            <div class="row mt-1">
                <div class="col add-form-element">
                    <label><i class="fa-regular fa-square-check"></i> Checkbox: </label>
                    <p>Single checkbox</p>
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-sm form-input" type="text" placeholder="Enter Name"
                               aria-label="Enter Name" aria-describedby="button-addon2" id="checkboxName">
                        <button class="btn btn-secondary" id="button-addon2" type="button"
                                onclick="addInput('checkbox', 'checkboxName')">Add
                        </button>
                    </div>
                </div>
                <div class="col add-form-element">
                    <label><i class="fa-solid fa-list-check"></i> Checkbox Many : </label>
                    <p>Multi-checkbox</p>
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-sm form-input" type="text" placeholder="Enter Name"
                               id="multi_checkboxName" aria-label="Enter Name" aria-describedby="button-addon2">
                        <button class="btn btn-secondary" id="button-addon2" type="button"
                                onclick="addInput('multi-checkbox','multi_checkboxName')">Add
                        </button>

                    </div>
                </div>
            </div>
            {{--<div class="row mt-1">
                <div class="col add-form-element">
                    <label><i class="fa-regular fa-circle-dot"></i> Radio Group: </label>
                    <p>Radio Group</p>
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-sm form-input" type="text" placeholder="Enter Name"
                            aria-label="Enter Name" aria-describedby="button-addon2" id="radioName">
                        <button class="btn btn-secondary" id="button-addon2" type="button"
                            onclick="addInput('radio', 'radioName')">Add
                        </button>
                    </div>
                </div>
                <div class="col add-form-element">
                    <label><i class="fa-regular fa-circle-dot"></i> Custom Radio Group : </label>
                    <p>Custom Radio </p>
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-sm form-input" type="text" placeholder="Enter Name"
                            id="customRadio" aria-label="Enter Name" aria-describedby="button-addon2">
                        <button class="btn btn-secondary" id="button-addon2" type="button"
                            onclick="addInput('customRadio','customRadio')">Add
                        </button>

                    </div>
                </div>
            </div>--}}
            <div class="row mt-1">
                <div class="col add-form-element">
                    <label><i class="fa-regular fa-image"></i> Image: </label>
                    <p>Image upload</p>
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-sm form-input" type="text" placeholder="Enter Name"
                               aria-label="Enter Name" aria-describedby="button-addon2" id="imageName">
                        <button class="btn btn-secondary" id="button-addon2" type="button"
                                onclick="addInput('image_upload', 'imageName')">Add
                        </button>
                    </div>
                </div>
                <div class="col add-form-element">
                    <label><i class="fa-solid fa-file-arrow-up"></i> File : </label>
                    <p>Upload documents & file</p>
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-sm form-input" type="text" placeholder="Enter Name"
                               id="fileName" aria-label="Enter Name" aria-describedby="button-addon2">
                        <button class="btn btn-secondary" id="button-addon2" type="button"
                                onclick="addInput('file_upload','fileName')">Add
                        </button>

                    </div>
                </div>
            </div>
            <div class="row mt-1">
                <div class="col add-form-element">
                    <label><i class="fa-solid fa-bars"></i> Select :</label>
                    <p>Simple dropdown</p>
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-sm form-input" type="text" placeholder="Enter Name"
                               id="selectName" aria-label="Enter Name" aria-describedby="button-addon2">
                        <button class="btn btn-secondary" id="button-addon2" type="button"
                                onclick="addInput('select','selectName')">Add
                        </button>
                    </div>
                </div>
                <div class="col add-form-element">
                    <label><i class="fa-solid fa-grip"></i> Select 2 :</label>
                    <p>Dropdown with search</p>
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-sm form-input" type="text" placeholder="Enter Name"
                               id="selectName2" aria-label="Enter Name" aria-describedby="button-addon2">
                        <button class="btn btn-secondary" id="button-addon2" type="button"
                                onclick="addInput('select2','selectName2')">Add
                        </button>
                    </div>
                </div>

            </div>
            {{-- <div class="row mt-1">
                 <div class="col add-form-element">
                  <label><i class="fa-solid fa-bars"></i> Custom Select : </label>
                  <p>Custom simple dropdown</p>
                  <div class="input-group input-group-sm">
                      <input class="form-control form-control-sm form-input" type="text" placeholder="Enter Name"
                          id="customSelectName" aria-label="Enter Name" aria-describedby="button-addon2">
                      <button class="btn btn-secondary" id="button-addon2" type="button"
                          onclick="addInput('select','customSelectName')">Add
                      </button>
                  </div>
              </div>
              <div class="col add-form-element">
                       <label><i class="fa-solid fa-grip"></i> Multi Select 2 : </label>
                       <p>Multi Select with search</p>
                       <div class="input-group input-group-sm">
                           <input class="form-control form-control-sm form-input" type="text" placeholder="Enter Name"
                               id="multiSelectName" aria-label="Enter Name" aria-describedby="button-addon2">
                           <button class="btn btn-secondary" id="button-addon2" type="button"
                               onclick="addInput('multiselect','multiSelectName')">Add
                           </button>
                       </div>
                   </div>
             </div>--}}
            <div class="row mt-1">
                <div class="col add-form-element">
                    <label><i class="fa-solid fa-font"></i> Header :</label>
                    <p>Dropdown with search</p>
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-sm form-input" type="text" placeholder="Enter Name"
                               id="selectName2" aria-label="Enter Name" aria-describedby="button-addon2">
                        <button class="btn btn-secondary" id="button-addon2" type="button"
                                onclick="addInput('select2','selectName2')">Add
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" id="offcanvasRight"
         aria-labelledby="offcanvasRightLabel" data-bs-scroll="true" data-bs-backdrop="static">
        <div class="offcanvas-header">
            <h5 id="offcanvasRightLabel" style="font-weight: bold;"><span id="input_type">Title Box</span> Setting
            </h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close">X
            </button>
        </div>
        <div class="offcanvas-body" style="overflow-y: auto;max-height: 90vh;">
            <form action="{{ route('form_element_update') }}" id="updateFormElement" method="POST">
                @csrf
                <input type="hidden" id="form_id" name="form_id">
                <div class="form-group">
                    <label for="">Label Name :</label>
                    <input type="text" class="form-input" id="label_name" name="label_name">
                </div>
                <div class="form-group mt-2">
                    <label for="">Column Name :</label>
                    <input type="text" class="form-input" id="column_name" name="column_name">
                </div>
                <div class="form-group mt-2">
                    <label for="">Placeholder:</label>
                    <input type="text" class="form-input" id="placeholder" name="placeholder">
                </div>
                <div class="form-group mt-2">
                    <label for="">Default Value :</label>
                    <input type="text" class="form-input" id="default_value" name="default_value">
                </div>
                <div class="form-group mt-2">
                    <div class="ak-form-label"><label>Width</label></div>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="width" id="row-100" value="row-100">
                        <label class="form-check-label" for="row-100">100%</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="width" id="row-75" value="row-75">
                        <label class="form-check-label" for="row-75">75%</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="width" id="row-66" value="row-66">
                        <label class="form-check-label" for="row-66">66%</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="width" id="row-50" value="row-50">
                        <label class="form-check-label" for="row-50">50%</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="width" id="row-33" value="row-33">
                        <label class="form-check-label" for="row-33">33%</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="width" id="row-25" value="row-25">
                        <label class="form-check-label" for="row-25">25%</label>
                    </div>
                </div>
                <div class="form-group mt-3">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="is_required" name="is_required"
                               value="N">
                        <label class="form-check-label" for="is_required">Required</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="is_unique" name="is_unique" value="N">
                        <label class="form-check-label" for="is_unique">Unique</label>
                    </div>
                </div>
                <div class="form-group mt-2">
                    <label for="">Type</label>
                    <select name="column_type" id="column_type" class="form-select">
                        <option value="date">Date</option>
                        <option value="date_time">Date & Time</option>
                    </select>
                </div>
                <div class="form-group mt-2">
                    <label for="">Source Table</label>
                    <select name="source_table" id="source_table" class="form-select">
                        <option value="">Select Source</option>
                        @if ($source_table)
                            @foreach ($source_table as $key => $value)
                                <option value="{{ $value }}">{{ $value }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="form-group mt-2">
                    <div class="row">
                        <div class="col">
                            <label for="">Key</label>
                            <input type="text" class="form-input" id="source_table_key" name="source_table_key">
                        </div>
                        <div class="col">
                            <label for="">Value</label>
                            <input type="text" class="form-input" id="source_table_value" name="source_table_value">
                        </div>
                    </div>
                </div>
                <div class="form-group mt-2">

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="is_switch" name="is_switch" value="N">
                        <label class="form-check-label" for="is_switch">Display as switch</label>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col"></div>
                    <div class="col" style="text-align: -webkit-right">
                        <button type="submit" class="button primary-button">Save Changes</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
    <input type="hidden" name="page_id" id="page_id" value="{{ $page['id'] }}">
    <input type="hidden" name="page_name" id="page_name" value="{{ $page['page_name'] }}">
@endsection
@push('bottom_script')
    <script>
        $(document).ready(function () {
            $(".dynamicForm").sortable({
                update: function (event, ui) {
                    var sortedIDs = $(".dynamicForm").sortable("toArray", {
                        attribute: "data-id"
                    });
                    console.log(sortedIDs);
                    // Send sortedIDs to server
                    $.ajax({
                        url: '{{ route('update_sorting_order') }}',
                        method: 'POST',
                        data: {
                            order: sortedIDs
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            console.log('Order updated successfully');
                        },
                        error: function (xhr, status, error) {
                            console.error('Error updating order:', error);
                        }
                    });
                }
            });
            $(".dynamicForm").disableSelection();

        });

        // Function to convert a string to snake_case
        function toSnakeCase(str) {
            return str.toLowerCase().replace(/[\s-]+/g, '_').replace(/[^\w_]/g, '');
        }

        function convert_space_to_underscore(str) {
            return str.replace(/\s+/g, '_').toLowerCase();
        }

        function addInput(type, nameId) {
            const name = document.getElementById(nameId).value;
            if (name.trim() === '') {
                alert('Please enter a name for the input field.');
                return;
            }
            const label = name;
            //ajax call to save form element in table
            $.ajax({
                url: "{{ route('add_form_element') }}",
                type: 'POST',
                data: {
                    page_id: $("#page_id").val(),
                    page_name: $("#page_name").val(),
                    type: type,
                    name: convert_space_to_underscore(name),
                    placeholder: label,
                },
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function () {

                },
                success: function (data) {
                    if (data.status === 200) {
                        window.location.reload();
                    }
                }
            });
        }

        $(document).on('click', '#generateForm', function () {
            $.ajax({
                url: "{{ route('generate_form') }}",
                type: 'POST',
                data: {
                    page_id: $("#page_id").val(),
                    page_name: $("#page_name").val(),
                },
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function () {

                },
                success: function (data) {
                    if (data.status == 200) {
                        ToastStart.success("Form Generated Successfully");
                    } else {
                        ToastStart.error("Something went wrong,please try again later!!");
                    }
                }
            });
        });

        function getFormDetails(form_id) {
            $.ajax({
                url: "{{ route('get_form_element_details') }}",
                type: 'POST',
                data: {
                    form_id: form_id,
                },
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    if (data.status == 200) {
                        const elementDetails = data.data;
                        $("#form_id").val(form_id);
                        $("#label_name").val(elementDetails.column_title);
                        $("#column_name").val(elementDetails.column_name);
                        $("#placeholder").val(elementDetails.placeholder);
                        $("#default_value").val(elementDetails.default_value);
                        $("#source_table").val(elementDetails.source_table);
                        $("#source_table_key").val(elementDetails.source_table_column_key);
                        $("#source_table_value").val(elementDetails.source_table_column_value);

                        // Handle column_width radio button selection
                        if (elementDetails.column_width === 'row-100') {
                            $('#updateFormElement').find(':radio[name="width"][value="row-100"]').prop(
                                'checked', true);
                        } else if (elementDetails.column_width === 'row-75') {
                            $('#updateFormElement').find(':radio[name="width"][value="row-75"]').prop('checked',
                                true);
                        } else if (elementDetails.column_width === 'row-66') {
                            $('#updateFormElement').find(':radio[name="width"][value="row-66"]').prop('checked',
                                true);
                        } else if (elementDetails.column_width === 'row-50') {
                            $('#updateFormElement').find(':radio[name="width"][value="row-50"]').prop('checked',
                                true);
                        } else if (elementDetails.column_width === 'row-33') {
                            $('#updateFormElement').find(':radio[name="width"][value="row-33"]').prop('checked',
                                true);
                        } else if (elementDetails.column_width === 'row-25') {
                            $('#updateFormElement').find(':radio[name="width"][value="row-25"]').prop('checked',
                                true);
                        }

                        setCheckboxProperties('is_required', elementDetails.is_required);
                        setCheckboxProperties('is_unique', elementDetails.is_unique);
                        setCheckboxProperties('is_switch', elementDetails.is_switch);
                    } else {
                        console.error('Failed to fetch form details:', data.message);
                        // Handle error case if needed
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching form details:', error);
                    // Handle error case if needed
                }
            });
        }

        function setCheckboxProperties(elementId, elementValue) {
            const isChecked = elementValue === 'Y';
            $(`#${elementId}`).prop('checked', isChecked).val(elementValue);
        }

        $('#is_required').click(function () {
            $(this).val(this.checked ? 'Y' : 'N');
        });
        $('#is_unique').click(function () {
            $(this).val(this.checked ? 'Y' : 'N');
        });
        $('#is_switch').click(function () {
            $(this).val(this.checked ? 'Y' : 'N');
        });

        $(document).on("click", ".delete-btn", function () {
            var form_id = $(this).data('form_id');
            $.ajax({
                url: "{{ route('form_element_delete') }}",
                type: 'POST',
                data: {
                    form_id: form_id,
                },
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    if (data.status == 200) {
                        window.location.reload();
                    } else {
                        alert('Something went wrong!!');
                    }
                }
            });
        });
    </script>
@endpush
