@extends('layouts.app')
@push('breadcrumb')
    <li class="breadcrumb-item">Mapping Builder</li>
    <li class="breadcrumb-item active"> {{ $page['mapping_name'] }}</li>
@endpush
@push('page-back-button')
    <div class="page-back-button">
        <a href="{{ route("mapping-builder.index") }}">
            <div class="icon">
                <div class="font-awesome-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512">
                        <path
                            d="M192 448c-8.188 0-16.38-3.125-22.62-9.375l-160-160c-12.5-12.5-12.5-32.75 0-45.25l160-160c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25L77.25 256l137.4 137.4c12.5 12.5 12.5 32.75 0 45.25C208.4 444.9 200.2 448 192 448z"/>
                    </svg>
                </div>
            </div>
            <div>Back</div>
        </a>
    </div>
@endpush
@push('upper_style')
    <style>

        .parent > .row {

            .col img {
                height: 120px;
                width: 100%;
                cursor: pointer;
                transition: transform 1s;
                object-fit: cover;
            }

            .col label {
                overflow: hidden;
                position: relative;
            }

            .imgbgchk:checked + label > .tick_container {
                opacity: 1;
            }

            .imgbgchk:checked + label > img {
                transform: scale(1.25);
                opacity: 1;
            }

            .tick_container {
                transition: .5s ease;
                opacity: 0;
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                -ms-transform: translate(-50%, -50%);
                cursor: pointer;
                text-align: center;
            }

            .tick {
                background-color: #4CAF50;
                color: white;
                font-size: 16px;
                padding: 6px 12px;
                height: 40px;
                width: 40px;
                border-radius: 100%;
            }

        }


    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
@endpush
@section('content')
    <div class="form-container content-width-full state-form-content js-ak-delete-container">
        <form method="POST"
              action="{{route('generate_mapping_builder')}}"
              enctype="multipart/form-data" class="form-page validate-form" novalidate>
            <div hidden>
                @csrf
                <input type="hidden" id="mapping_id" name="mapping_id" value="{{$page_id}}">
            </div>
            <div class="form-header">
                <h3>Update Mapping Form</h3>

            </div>
            @includeIf("layouts.errors")
            <div class="form-content">
                <div class="row-25">
                    <div class="input-container">
                        <div class="input-label">
                            <label for="parent_column">Parent Table Column <span class="required">*</span></label>
                        </div>
                        <div class="input-data">
                            <select name="parent_column" id="parent_column" class="form-select">
                                <option value="">Select Column</option>
                                @foreach($parent_table_columns as $list)
                                    <option
                                        value="{{$list->column_name}}" {{$data->parent_column == $list->column_name ?'selected':''}}>{{$list->column_title}}</option>
                                @endforeach
                            </select>
                            <div class="error-message @if ($errors->has('parent_column')) show @endif">
                                Required!
                            </div>
                            <div class="text-muted" id="parent_column_help"></div>
                        </div>
                    </div>
                </div>
                <div class="row-50">
                    <div class="input-container">
                        <div class="input-label">
                            <label for="child_column">Child Table Column <span class="required">*</span></label>
                        </div>
                        <div class="input-data">
                            <select name="child_column[]" id="child_column" class="form-select js-ak-select2-many"
                                    multiple>
                                <option value="">Select Column</option>
                                @php
                                    $selectedColumns = explode(',', $data->child_column);
                                @endphp
                                @foreach($child_table_columns as $list)
                                    <option
                                        value="{{$list->column_name}}" {{ in_array($list->column_name, $selectedColumns) ? 'selected' : '' }}>
                                        {{$list->column_title}}
                                    </option>
                                @endforeach
                            </select>
                            <div class="error-message @if ($errors->has('child_column')) show @endif">
                                Required!
                            </div>
                            <div class="text-muted" id="child_column_help"></div>
                        </div>
                    </div>
                </div>


                <div class="container-fluid parent">
                    <div class="row">
                        <div class='col  col-md-2 text-center'>
                            <input type="radio" name="mapping_type" id="img1" class="d-none imgbgchk"
                                   value="1" {{$data->mapping_type == 1 ?'checked':''}} checked>
                            <label for="img1">
                                <img src="{{URL::to('/')}}/assets/images/type_one.jpg" alt="Image 1">
                                <div class="tick_container">
                                    <div class="tick"><i class="fa fa-check"></i></div>
                                </div>
                            </label>
                        </div>
                        {{-- <div class='col col-md-2 text-center'>
                             <input type="radio" name="mapping_type" id="img2" class="d-none imgbgchk" value="2" {{$data->mapping_type == 2 ?'checked':''}}>
                             <label for="img2">
                                 <img src="{{URL::to('/')}}/assets/images/type_two.jpg" alt="Image 2">
                                 <div class="tick_container">
                                     <div class="tick"><i class="fa fa-check"></i></div>
                                 </div>
                             </label>
                         </div>--}}

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

