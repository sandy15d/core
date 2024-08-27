@extends('layouts.app')
@push('breadcrumb')
    <li class="breadcrumb-item">Form</li>
    <li class="breadcrumb-item active"> Company Address</li>
@endpush

@push('page-back-button')
    <div class="page-back-button">
        <a href="{{ route("company_address.index") }}">
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
    <div class="form-container content-width-full company_address-form-content js-ak-delete-container">
        <form method="POST" action="@isset($data->id){{route("company_address.update", $data->id)}}@else{{route("company_address.store")}}@endisset" enctype="multipart/form-data" class="form-page validate-form" novalidate>
            <div hidden>
                @isset($data->id) @method('PUT') @endisset
                @csrf
            </div>
             <div class="form-header">
                <h3>{{ isset($data->id) ? 'Update' : 'Add New' }}</h3>
                @can('delete-CompanyAddress')
                <div class="form-delete-record">
                    @if(isset($data->id))
                        <a href="#" data-link="{{route('company_address.destroy',$data->id)}}" data-id="{{$data->id}}" class="delete-link js-ak-delete-link" draggable="false">
                            @includeIf("layouts.icons.delete_icon")
                        </a>
                    @endIf
                </div>
                @endcan
            </div>
            @includeIf("layouts.errors")
            <div class="form-content">
                    <div class="row-66">
        <div class="input-container">
            <div class="input-label">
                <label for="company_id">Company <span class="required">*</span></label>
            </div>
            <div class="input-data">
                <select name="company_id" id="company_id" class="form-select js-ak-select2">
                    <option value="">Select Company</option><br>@foreach ($company_list as $list)
                                        <option value="{{$list->id}}" {{ $data->company_id == $list->id ? "selected" : "" }}>{{ $list->company_name }}</option>
                                     @endforeach
                </select>
                <div class="error-message @if ($errors->has('company_id')) show @endif">
                    Required!</div>
               <div class="text-muted" id="company_id_help"></div>
            </div>
        </div>
    </div>    <div class="row-33">
        <div class="input-container">
            <div class="input-label">
                <label for="address_type">Address Type <span class="required">*</span></label>
            </div>
            <div class="input-data">
                <input type="text" class="form-input " id="address_type" autocomplete="off"
                    name="address_type" placeholder="Address Type"
                    value="{{ old('address_type', $data->address_type ?? '') }}"   />
                <div class="error-message @if ($errors->has('address_type')) show @endif">
                    Required!</div>
                <div class="text-muted" id="address_type_help">
                    
                </div>
            </div>
        </div>
    </div>    <div class="row-100">
        <div class="input-container">
            <div class="input-label">
                <label for="address">Address <span class="required">*</span></label>
            </div>
            <div class="input-data">
                <textarea class="form-input form-textarea js-ak-tiny-mce-simple-text-editor" id="address" name="address" data-height="250" style="height:250px" name="address"  placeholder="Address">{{ old('address', $data->address ?? '') }}</textarea>
                <div class="error-message @if ($errors->has('address')) show @endif">
                    Required!</div>
                <div class="text-muted" id="address_help"></div>
            </div>
        </div>
    </div>    <div class="row-33">
        <div class="input-container">
            <div class="input-label">
                <label for="country_id">Country <span class="required">*</span></label>
            </div>
            <div class="input-data">
                <select name="country_id" id="country_id" class="form-select">
                    <option value="">Select Country</option>
                         @foreach ($country_list as $list)
                            <option value="{{$list->id}}" {{ $data->country_id == $list->id ? "selected" : "" }}>{{ $list->country_name }}</option>
                         @endforeach
                </select>
                <div class="error-message @if ($errors->has('country_id')) show @endif">
                    Required!</div>
                <div class="text-muted" id="country_id_help"></div>
            </div>
        </div>
    </div>    <div class="row-33">
        <div class="input-container">
            <div class="input-label">
                <label for="state_id">State <span class="required">*</span></label>
            </div>
            <div class="input-data">
                <select name="state_id" id="state_id" class="form-select js-ak-select2">
                    <option value="">Select State</option>
                </select>
                <div class="error-message @if ($errors->has('state_id')) show @endif">
                    Required!</div>
               <div class="text-muted" id="state_id_help"></div>
            </div>
        </div>
    </div>    <div class="row-33">
        <div class="input-container">
            <div class="input-label">
                <label for="district_id">District <span class="required">*</span></label>
            </div>
            <div class="input-data">
                <select name="district_id" id="district_id" class="form-select js-ak-select2">
                    <option value="">Select District</option>
                </select>
                <div class="error-message @if ($errors->has('district_id')) show @endif">
                    Required!</div>
               <div class="text-muted" id="district_id_help"></div>
            </div>
        </div>
    </div>    <div class="row-33">
        <div class="input-container">
            <div class="input-label">
                <label for="city_id">City <span class="required">*</span></label>
            </div>
            <div class="input-data">
                <select name="city_id" id="city_id" class="form-select js-ak-select2">
                    <option value="">Select City</option>
                    <br>
                    
                </select>
                <div class="error-message @if ($errors->has('city_id')) show @endif">
                    Required!</div>
               <div class="text-muted" id="city_id_help"></div>
            </div>
        </div>
    </div>    <div class="row-25">
        <div class="input-container">
            <div class="input-label">
                <label for="pin_code">Pin Code <span class="required">*</span></label>
            </div>
            <div class="input-data">
                <input type="text" class="form-input " id="pin_code" autocomplete="off"
                    name="pin_code" placeholder="Pin Code"
                    value="{{ old('pin_code', $data->pin_code ?? '') }}"   />
                <div class="error-message @if ($errors->has('pin_code')) show @endif">
                    Required!</div>
                <div class="text-muted" id="pin_code_help">
                    
                </div>
            </div>
        </div>
    </div>
            </div>
            @includeIf("layouts.form_footer",["cancel_route"=>route("company_address.index")])
        </form>

    </div>
    @isset($data->id)
        @includeIf("layouts.delete_modal_confirm")
    @endisset
@endsection
