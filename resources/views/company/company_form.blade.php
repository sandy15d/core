@extends('layouts.app')
@push('breadcrumb')
    <li class="breadcrumb-item">Form</li>
    <li class="breadcrumb-item active"> Company</li>
@endpush

@push('page-back-button')
    <div class="page-back-button">
        <a href="{{ route("company.index") }}">
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
    <div class="form-container content-width-full company-form-content js-ak-delete-container">
        <form method="POST" action="@isset($data->id){{route("company.update", $data->id)}}@else{{route("company.store")}}@endisset" enctype="multipart/form-data" class="form-page validate-form" novalidate>
            <div hidden>
                @isset($data->id) @method('PUT') @endisset
                @csrf
            </div>
             <div class="form-header">
                <h3>{{ isset($data->id) ? 'Update' : 'Add New' }}</h3>
                @can('delete-Company')
                <div class="form-delete-record">
                    @if(isset($data->id))
                        <a href="#" data-link="{{route('company.destroy',$data->id)}}" data-id="{{$data->id}}" class="delete-link js-ak-delete-link" draggable="false">
                            @includeIf("layouts.icons.delete_icon")
                        </a>
                    @endIf
                </div>
                @endcan
            </div>
            @includeIf("layouts.errors")
            <div class="form-content">
                    <div class="row-50">
        <div class="input-container">
            <div class="input-label">
                <label for="company_name">Company Name <span class="required">*</span></label>
            </div>
            <div class="input-data">
                <input type="text" class="form-input " id="company_name" autocomplete="off"
                    name="company_name" placeholder="Company Name"
                    value="{{ old('company_name', $data->company_name ?? '') }}"   />
                <div class="error-message @if ($errors->has('company_name')) show @endif">
                    Required!</div>
                <div class="text-muted" id="company_name_help">
                    
                </div>
            </div>
        </div>
    </div>    <div class="row-25">
        <div class="input-container">
            <div class="input-label">
                <label for="company_code">Company Code <span class="required">*</span></label>
            </div>
            <div class="input-data">
                <input type="text" class="form-input " id="company_code" autocomplete="off"
                    name="company_code" placeholder="Company Code"
                    value="{{ old('company_code', $data->company_code ?? '') }}"   />
                <div class="error-message @if ($errors->has('company_code')) show @endif">
                    Required!</div>
                <div class="text-muted" id="company_code_help">
                    
                </div>
            </div>
        </div>
    </div>    <div class="row-25">
        <div class="input-container">
            <div class="input-label">
                <label for="legal_entity_type">Legal Entity Type </label>
            </div>
            <div class="input-data">
                <input type="text" class="form-input " id="legal_entity_type" autocomplete="off"
                    name="legal_entity_type" placeholder="Legal Entity Type"
                    value="{{ old('legal_entity_type', $data->legal_entity_type ?? '') }}"   />
                <div class="error-message @if ($errors->has('legal_entity_type')) show @endif">
                    Required!</div>
                <div class="text-muted" id="legal_entity_type_help">
                    
                </div>
            </div>
        </div>
    </div>    <div class="row-33">
        <div class="input-container">
            <div class="input-label">
                <label for="registration_number">Registration Number </label>
            </div>
            <div class="input-data">
                <input type="text" class="form-input " id="registration_number" autocomplete="off"
                    name="registration_number" placeholder="Registration Number"
                    value="{{ old('registration_number', $data->registration_number ?? '') }}"   />
                <div class="error-message @if ($errors->has('registration_number')) show @endif">
                    Required!</div>
                <div class="text-muted" id="registration_number_help">
                    
                </div>
            </div>
        </div>
    </div>    <div class="row-33">
        <div class="input-container">
            <div class="input-label">
                <label for="gst_number">GST Number </label>
            </div>
            <div class="input-data">
                <input type="text" class="form-input " id="gst_number" autocomplete="off"
                    name="gst_number" placeholder="GST Number"
                    value="{{ old('gst_number', $data->gst_number ?? '') }}"   />
                <div class="error-message @if ($errors->has('gst_number')) show @endif">
                    Required!</div>
                <div class="text-muted" id="gst_number_help">
                    
                </div>
            </div>
        </div>
    </div>    <div class="row-33">
        <div class="input-container">
            <div class="input-label">
                <label for="tin_number">TIN Number </label>
            </div>
            <div class="input-data">
                <input type="text" class="form-input " id="tin_number" autocomplete="off"
                    name="tin_number" placeholder="TIN Number"
                    value="{{ old('tin_number', $data->tin_number ?? '') }}"   />
                <div class="error-message @if ($errors->has('tin_number')) show @endif">
                    Required!</div>
                <div class="text-muted" id="tin_number_help">
                    
                </div>
            </div>
        </div>
    </div>    <div class="row-50">
        <div class="input-container">
            <div class="input-label">
                <label for="logo">Logo </label>
            </div>
            <div class="input-data">
                 @if ($data->logo)
                        <div>
                            <a href="{{ $data->logo }}" target="_blank" class="form-image-preview js-ak-logo-available">
                                <img src="{{ $data->logo }}">
                            </a>
                        </div>
                        <div>
                            <label for="ak_logo_delete" class="checkbox-input">
                                <input class="form-checkbox" type="checkbox" name="ak_logo_delete" id="ak_logo_delete" value="1">
                               Remove file
                            </label>
                        </div>
                    @elseif(isset($data->logo) && $data->getRawOriginal("logo"))
                        <div class="alert-info-container">
                            <div>'{{$data->getRawOriginal("logo")}}' file can't be found. But its value exists in the database.</div>
                        </div>
                    @endif
                    <input type="file" class="form-file js-ak-image-upload" data-id="logo" accept=".jpg,.jpeg,.png,.webp" data-file-type=".jpg,.jpeg,.png,.webp"  name="logo"  data-selected="Selected image for upload:">
                    <input type="hidden" name="ak_logo_current" value="{{$data->getRawOriginal("logo")??''}}">
                    <div class="error-message @if ($errors->has('logo')) show @endif" data-required="Image is required!" data-size="Invalid file size!" data-type="Invalid file type!" data-size-type="Invalid file size or type!">
                        @if ($errors->has('logo')){{ $errors->first('logo') }}@endif
                    </div>
               <div class="text-muted" id="logo_help"></div>
            </div>
        </div>
    </div>    <div class="row-25">
        <div class="input-container">
            <div class="input-label">
                <label for="website">Website </label>
            </div>
            <div class="input-data">
                <input type="text" class="form-input " id="website" autocomplete="off"
                    name="website" placeholder="Website URL"
                    value="{{ old('website', $data->website ?? '') }}"   />
                <div class="error-message @if ($errors->has('website')) show @endif">
                    Required!</div>
                <div class="text-muted" id="website_help">
                    
                </div>
            </div>
        </div>
    </div>    <div class="row-25">
        <div class="input-container">
            <div class="input-label">
                <label for="email">Email </label>
            </div>
            <div class="input-data">
                <input type="email" class="form-input " id="email" autocomplete="off"
                    name="email" placeholder="Email"
                    value="{{ old('email', $data->email ?? '') }}"   />
                <div class="error-message @if ($errors->has('email')) show @endif">
                    Required!</div>
                <div class="text-muted" id="email_help">
                    
                </div>
            </div>
        </div>
    </div>
            </div>
            @includeIf("layouts.form_footer",["cancel_route"=>route("company.index")])
        </form>

    </div>
    @isset($data->id)
        @includeIf("layouts.delete_modal_confirm")
    @endisset
@endsection
