@extends('layouts.app')
@push('breadcrumb')
    <li class="breadcrumb-item">Form</li>
    <li class="breadcrumb-item active"> Company Contact</li>
@endpush

@push('page-back-button')
    <div class="page-back-button">
        <a href="{{ route("company_contact.index") }}">
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
    <div class="form-container content-width-full company_contact-form-content js-ak-delete-container">
        <form method="POST" action="@isset($data->id){{route("company_contact.update", $data->id)}}@else{{route("company_contact.store")}}@endisset" enctype="multipart/form-data" class="form-page validate-form" novalidate>
            <div hidden>
                @isset($data->id) @method('PUT') @endisset
                @csrf
            </div>
             <div class="form-header">
                <h3>{{ isset($data->id) ? 'Update' : 'Add New' }}</h3>
                @can('delete-CompanyContact')
                <div class="form-delete-record">
                    @if(isset($data->id))
                        <a href="#" data-link="{{route('company_contact.destroy',$data->id)}}" data-id="{{$data->id}}" class="delete-link js-ak-delete-link" draggable="false">
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
                <label for="company_id">Company <span class="required">*</span></label>
            </div>
            <div class="input-data">
                <select name="company_id" id="company_id" class="form-select">
                    <option value="">Select Company</option>@foreach ($company_list as $list)
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
                <label for="company_address_id">Company Address <span class="required">*</span></label>
            </div>
            <div class="input-data">
                <select name="company_address_id" id="company_address_id" class="form-select">
                    <option value="">Select Company Address</option>@foreach ($company_address_list as $list)
                                        <option value="{{$list->id}}" {{ $data->company_address_id == $list->id ? "selected" : "" }}>{{ $list->address_type }}</option>
                                     @endforeach
                </select>
                <div class="error-message @if ($errors->has('company_address_id')) show @endif">
                    Required!</div>
                <div class="text-muted" id="company_address_id_help"></div>
            </div>
        </div>
    </div>    <div class="row-33">
        <div class="input-container">
            <div class="input-label">
                <label for="contact_type">Contact Type <span class="required">*</span></label>
            </div>
            <div class="input-data">
                <input type="text" class="form-input " id="contact_type" autocomplete="off"
                    name="contact_type" placeholder="Contact Type"
                    value="{{ old('contact_type', $data->contact_type ?? '') }}"   />
                <div class="error-message @if ($errors->has('contact_type')) show @endif">
                    Required!</div>
                <div class="text-muted" id="contact_type_help">
                    
                </div>
            </div>
        </div>
    </div>    <div class="row-25">
        <div class="input-container">
            <div class="input-label">
                <label for="contact_person">Contact Person <span class="required">*</span></label>
            </div>
            <div class="input-data">
                <input type="text" class="form-input " id="contact_person" autocomplete="off"
                    name="contact_person" placeholder="Contact Person"
                    value="{{ old('contact_person', $data->contact_person ?? '') }}"   />
                <div class="error-message @if ($errors->has('contact_person')) show @endif">
                    Required!</div>
                <div class="text-muted" id="contact_person_help">
                    
                </div>
            </div>
        </div>
    </div>    <div class="row-25">
        <div class="input-container">
            <div class="input-label">
                <label for="designation">Designation </label>
            </div>
            <div class="input-data">
                <input type="text" class="form-input " id="designation" autocomplete="off"
                    name="designation" placeholder="Designation"
                    value="{{ old('designation', $data->designation ?? '') }}"   />
                <div class="error-message @if ($errors->has('designation')) show @endif">
                    Required!</div>
                <div class="text-muted" id="designation_help">
                    
                </div>
            </div>
        </div>
    </div>    <div class="row-25">
        <div class="input-container">
            <div class="input-label">
                <label for="phone_one">Phone One <span class="required">*</span></label>
            </div>
            <div class="input-data">
                <input type="text" class="form-input " id="phone_one" autocomplete="off"
                    name="phone_one" placeholder="Phone One"
                    value="{{ old('phone_one', $data->phone_one ?? '') }}"   />
                <div class="error-message @if ($errors->has('phone_one')) show @endif">
                    Required!</div>
                <div class="text-muted" id="phone_one_help">
                    
                </div>
            </div>
        </div>
    </div>    <div class="row-25">
        <div class="input-container">
            <div class="input-label">
                <label for="phone_two">Phone Two </label>
            </div>
            <div class="input-data">
                <input type="text" class="form-input " id="phone_two" autocomplete="off"
                    name="phone_two" placeholder="Phone Two"
                    value="{{ old('phone_two', $data->phone_two ?? '') }}"   />
                <div class="error-message @if ($errors->has('phone_two')) show @endif">
                    Required!</div>
                <div class="text-muted" id="phone_two_help">
                    
                </div>
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
            @includeIf("layouts.form_footer",["cancel_route"=>route("company_contact.index")])
        </form>

    </div>
    @isset($data->id)
        @includeIf("layouts.delete_modal_confirm")
    @endisset
@endsection
