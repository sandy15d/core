@extends('layouts.app')
@push('breadcrumb')
    <li class="breadcrumb-item">Form</li>
    <li class="breadcrumb-item active"> City Village</li>
@endpush

@push('page-back-button')
    <div class="page-back-button">
        <a href="{{ route("city_village.index") }}">
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
    <div class="form-container content-width-full city_village-form-content js-ak-delete-container">
        <form method="POST" action="@isset($data->id){{route("city_village.update", $data->id)}}@else{{route("city_village.store")}}@endisset" enctype="multipart/form-data" class="form-page validate-form" novalidate>
            <div hidden>
                @isset($data->id) @method('PUT') @endisset
                @csrf
            </div>
             <div class="form-header">
                <h3>{{ isset($data->id) ? 'Update' : 'Add New' }}</h3>
                @can('delete-CityVillage')
                <div class="form-delete-record">
                    @if(isset($data->id))
                        <a href="#" data-link="{{route('city_village.destroy',$data->id)}}" data-id="{{$data->id}}" class="delete-link js-ak-delete-link" draggable="false">
                            @includeIf("layouts.icons.delete_icon")
                        </a>
                    @endIf
                </div>
                @endcan
            </div>
            @includeIf("layouts.errors")
            <div class="form-content">
                    <div class="row-25">
        <div class="input-container">
            <div class="input-label">
                <label for="state_id">State <span class="required">*</span></label>
            </div>
            <div class="input-data">
                <select name="state_id" id="state_id" class="form-select js-ak-select2">
                    <option value="">Select State</option><br>@foreach ($state_list as $list)
                                        <option value="{{$list->id}}" {{ $data->state_id == $list->id ? "selected" : "" }}>{{ $list->state_name }}</option>
                                     @endforeach
                </select>
                <div class="error-message @if ($errors->has('state_id')) show @endif">
                    Required!</div>
               <div class="text-muted" id="state_id_help"></div>
            </div>
        </div>
    </div>    <div class="row-25">
        <div class="input-container">
            <div class="input-label">
                <label for="district_id">District <span class="required">*</span></label>
            </div>
            <div class="input-data">
                <select name="district_id" id="district_id" class="form-select js-ak-select2">
                    <option value="">Select District</option><br>@foreach ($district_list as $list)
                                        <option value="{{$list->id}}" {{ $data->district_id == $list->id ? "selected" : "" }}>{{ $list->district_name }}</option>
                                     @endforeach
                </select>
                <div class="error-message @if ($errors->has('district_id')) show @endif">
                    Required!</div>
               <div class="text-muted" id="district_id_help"></div>
            </div>
        </div>
    </div>    <div class="row-25">
        <div class="input-container">
            <div class="input-label">
                <label for="division_name">Division Name </label>
            </div>
            <div class="input-data">
                <input type="text" class="form-input " id="division_name" autocomplete="off"
                    name="division_name" placeholder="Division Name"
                    value="{{ old('division_name', $data->division_name ?? '') }}"   />
                <div class="error-message @if ($errors->has('division_name')) show @endif">
                    Required!</div>
                <div class="text-muted" id="division_name_help">
                    
                </div>
            </div>
        </div>
    </div>    <div class="row-25">
        <div class="input-container">
            <div class="input-label">
                <label for="city_village_name">City/Village Name <span class="required">*</span></label>
            </div>
            <div class="input-data">
                <input type="text" class="form-input " id="city_village_name" autocomplete="off"
                    name="city_village_name" placeholder="City/Village Name"
                    value="{{ old('city_village_name', $data->city_village_name ?? '') }}"   />
                <div class="error-message @if ($errors->has('city_village_name')) show @endif">
                    Required!</div>
                <div class="text-muted" id="city_village_name_help">
                    
                </div>
            </div>
        </div>
    </div>    <div class="row-25">
        <div class="input-container">
            <div class="input-label">
                <label for="pincode">Pincode <span class="required">*</span></label>
            </div>
            <div class="input-data">
                <input type="number" class="form-input " id="pincode" autocomplete="off"
                    name="pincode" placeholder="Pincode"
                    value="{{ old('pincode', $data->pincode ?? '') }}"   />
                <div class="error-message @if ($errors->has('pincode')) show @endif">
                    Required!</div>
                <div class="text-muted" id="pincode_help">
                    
                </div>
            </div>
        </div>
    </div>    <div class="row-25">
        <div class="input-container">
            <div class="input-label">
                <label for="longitude">Longitude </label>
            </div>
            <div class="input-data">
                <input type="text" class="form-input " id="longitude" autocomplete="off"
                    name="longitude" placeholder="Longitude"
                    value="{{ old('longitude', $data->longitude ?? '') }}"   />
                <div class="error-message @if ($errors->has('longitude')) show @endif">
                    Required!</div>
                <div class="text-muted" id="longitude_help">
                    
                </div>
            </div>
        </div>
    </div>    <div class="row-25">
        <div class="input-container">
            <div class="input-label">
                <label for="latitude">Latitude </label>
            </div>
            <div class="input-data">
                <input type="text" class="form-input " id="latitude" autocomplete="off"
                    name="latitude" placeholder="Latitude"
                    value="{{ old('latitude', $data->latitude ?? '') }}"   />
                <div class="error-message @if ($errors->has('latitude')) show @endif">
                    Required!</div>
                <div class="text-muted" id="latitude_help">
                    
                </div>
            </div>
        </div>
    </div>    <div class="row-25">
        <div class="input-container">
            <div class="input-label">
                <label for="effective_date">Effective Date </label>
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
                <label for="is_active">Is Active </label>
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
            @includeIf("layouts.form_footer",["cancel_route"=>route("city_village.index")])
        </form>

    </div>
    @isset($data->id)
        @includeIf("layouts.delete_modal_confirm")
    @endisset
@endsection
