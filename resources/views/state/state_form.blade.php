@extends('layouts.app')
@push('breadcrumb')
    <li class="breadcrumb-item">Form</li>
    <li class="breadcrumb-item active"> State</li>
@endpush
@push('page-title')
State
@endpush
@push('page-back-button')
    <div class="page-back-button">
        <a href="{{ route("state.index") }}">
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
    <div class="form-container content-width-full state-form-content js-ak-delete-container">
        <form method="POST" action="@isset($data->id){{route("state.update", $data->id)}}@else{{route("state.store")}}@endisset" enctype="multipart/form-data" class="form-page validate-form" novalidate>
            <div hidden>
                @isset($data->id) @method('PUT') @endisset
                @csrf
            </div>
             <div class="form-header">
                <h3>{{ isset($data->id) ? 'Update' : 'Add New' }}</h3>
                @can('delete-State')
                <div class="form-delete-record">
                    @if(isset($data->id))
                        <a href="#" data-link="{{route('state.destroy',$data->id)}}" data-id="{{$data->id}}" class="delete-link js-ak-delete-link" draggable="false">
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
                <label for="state_name">State Name <span class="required">*</span></label>
            </div>
            <div class="input-data">
                <input type="text" class="form-input" id="state_name" autocomplete="off"
                    name="state_name" placeholder="State Name"
                    value="{{ old('state_name', $data->state_name ?? '') }}">
                <div class="error-message @if ($errors->has('state_name')) show @endif">
                    Required!</div>
                <div class="text-muted" id="state_name_help"></div>
            </div>
        </div>
    </div>    <div class="row-50">
        <div class="input-container">
            <div class="input-label">
                <label for="state_code">State Code <span class="required">*</span></label>
            </div>
            <div class="input-data">
                <input type="text" class="form-input" id="state_code" autocomplete="off"
                    name="state_code" placeholder="State Code"
                    value="{{ old('state_code', $data->state_code ?? '') }}">
                <div class="error-message @if ($errors->has('state_code')) show @endif">
                    Required!</div>
                <div class="text-muted" id="state_code_help"></div>
            </div>
        </div>
    </div>    <div class="row-50">
        <div class="input-container">
            <div class="input-label">
                <label for="country_id">Country <span class="required">*</span></label>
            </div>
            <div class="input-data">
                <select name="country_id" id="country_id" class="form-select js-ak-select2">
                    <option value="">Select Country</option>@foreach ($Country_list as $list)
                                        <option value="{{$list->id}}" {{ $data->country_id == $list->id ? "selected" : "" }}>{{ $list->country_name }}</option>
                                     @endforeach
                </select>
                <div class="error-message @if ($errors->has('country_id')) show @endif">
                    Required!</div>
                <div class="text-muted" id="country_id_help"></div>
            </div>
        </div>
    </div>    <div class="row-50">
        <div class="input-container">
            <div class="input-label">
                <label for="is_active">Is Active <span class="required">*</span></label>
            </div>
             <div class="checkbox-input  form-switch">
                        <input type="hidden" name="is_active" value="0">
                        <input class="form-checkbox" type="checkbox" id="is_active" name="is_active" value="1"
                               @if(old("is_active") || ((isset($data->is_active)&&$data->is_active==1))) checked @endif >
                        <label class="form-check-label" for="is_active"></label>
             </div>
        </div>
    </div>
            </div>
            @includeIf("layouts.form_footer",["cancel_route"=>route("state.index")])
        </form>

    </div>
    @isset($data->id)
        @includeIf("layouts.delete_modal_confirm")
    @endisset
@endsection
