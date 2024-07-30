@extends('layouts.app')
@push('breadcrumb')
    <li class="breadcrumb-item">Form</li>
    <li class="breadcrumb-item active"> Business Unit</li>
@endpush

@push('page-back-button')
    <div class="page-back-button">
        <a href="{{ route("business_unit.index") }}">
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
    <div class="form-container content-width-full business_unit-form-content js-ak-delete-container">
        <form method="POST" action="@isset($data->id){{route("business_unit.update", $data->id)}}@else{{route("business_unit.store")}}@endisset" enctype="multipart/form-data" class="form-page validate-form" novalidate>
            <div hidden>
                @isset($data->id) @method('PUT') @endisset
                @csrf
            </div>
             <div class="form-header">
                <h3>{{ isset($data->id) ? 'Update' : 'Add New' }}</h3>
                @can('delete-BusinessUnit')
                <div class="form-delete-record">
                    @if(isset($data->id))
                        <a href="#" data-link="{{route('business_unit.destroy',$data->id)}}" data-id="{{$data->id}}" class="delete-link js-ak-delete-link" draggable="false">
                            @includeIf("layouts.icons.delete_icon")
                        </a>
                    @endIf
                </div>
                @endcan
            </div>
            @includeIf("layouts.errors")
            <div class="form-content">
                    <div class="row-33">
        <div class="input-container">
            <div class="input-label">
                <label for="vertical_id">Vertical Id <span class="required">*</span></label>
            </div>
            <div class="input-data">
                <select name="vertical_id" id="vertical_id" class="form-select js-ak-select2">
                    <option value="">Select Vertical Id</option><br>@foreach ($vertical_list as $list)
                                        <option value="{{$list->id}}" {{ $data->vertical_id == $list->id ? "selected" : "" }}>{{ $list->vertical_name }}</option>
                                     @endforeach
                </select>
                <div class="error-message @if ($errors->has('vertical_id')) show @endif">
                    Required!</div>
               <div class="text-muted" id="vertical_id_help"></div>
            </div>
        </div>
    </div>    <div class="row-33">
        <div class="input-container">
            <div class="input-label">
                <label for="business_unit_name">Business Unit Name <span class="required">*</span></label>
            </div>
            <div class="input-data">
                <input type="text" class="form-input " id="business_unit_name" autocomplete="off"
                    name="business_unit_name" placeholder="Business Unit Name"
                    value="{{ old('business_unit_name', $data->business_unit_name ?? '') }}"   />
                <div class="error-message @if ($errors->has('business_unit_name')) show @endif">
                    Required!</div>
                <div class="text-muted" id="business_unit_name_help">
                    
                </div>
            </div>
        </div>
    </div>    <div class="row-33">
        <div class="input-container">
            <div class="input-label">
                <label for="business_unit_code">Business Unit Code <span class="required">*</span></label>
            </div>
            <div class="input-data">
                <input type="text" class="form-input " id="business_unit_code" autocomplete="off"
                    name="business_unit_code" placeholder="Business Unit Code"
                    value="{{ old('business_unit_code', $data->business_unit_code ?? '') }}"   />
                <div class="error-message @if ($errors->has('business_unit_code')) show @endif">
                    Required!</div>
                <div class="text-muted" id="business_unit_code_help">
                    
                </div>
            </div>
        </div>
    </div>    <div class="row-25">
        <div class="input-container">
            <div class="input-label">
                <label for="numeric_code">Numeric Code </label>
            </div>
            <div class="input-data">
                <input type="number" class="form-input " id="numeric_code" autocomplete="off"
                    name="numeric_code" placeholder="Numeric Code"
                    value="{{ old('numeric_code', $data->numeric_code ?? '') }}"   />
                <div class="error-message @if ($errors->has('numeric_code')) show @endif">
                    Required!</div>
                <div class="text-muted" id="numeric_code_help">
                    
                </div>
            </div>
        </div>
    </div>    <div class="row-25">
        <div class="input-container">
            <div class="input-label">
                <label for="effective_date">Effective Date <span class="required">*</span></label>
            </div>
            <div class="input-data">
                 <div class="group-input date-time-group" id="ak_date_group_effective_date">
                    <input type="text" name="effective_date"
                           autocomplete="off" id="effective_date"
                           class="form-input js-ak-date-picker"
                           placeholder="Effective Date" value="{{ old('effective_date', isset($data->effective_date)?$data->getRawOriginal('effective_date') : '') }}">
                    <div class="input-suffix js-ak-calendar-icon"
                         data-target="#effective_date">
                        @includeIf("layouts.icons.calendar_icon")
                    </div>
                    <div class="error-message @if ($errors->has('effective_date')) show @endif">
                        Required!</div>
                </div>
                <div class="text-muted" id="effective_date_help"></div>
            </div>
        </div>
    </div>    <div class="row-25">
        <div class="input-container">
            <div class="input-label">
                <label for="is_active">Is Active <span class="required">*</span></label>
            </div>
            <div class="input-data">
             <div class="checkbox-input  form-switch">
                        <input type="hidden" name="is_active" value="0">
                        <input class="form-checkbox" type="checkbox" id="is_active" name="is_active" value="1"
                               @if(old("is_active") || ((isset($data->is_active)&&$data->is_active==1))) checked @endif >
                        <label class="form-check-label" for="is_active"></label>
             </div>
              <div class="text-muted" id="is_active_help"></div>
             </div>
        </div>
    </div>
            </div>
            @includeIf("layouts.form_footer",["cancel_route"=>route("business_unit.index")])
        </form>

    </div>
    @isset($data->id)
        @includeIf("layouts.delete_modal_confirm")
    @endisset
@endsection
