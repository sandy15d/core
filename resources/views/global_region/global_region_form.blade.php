@extends('layouts.app')
@push('breadcrumb')
    <li class="breadcrumb-item">Form</li>
    <li class="breadcrumb-item active"> Global Region</li>
@endpush

@push('page-back-button')
    <div class="page-back-button">
        <a href="{{ route("global_region.index") }}">
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
    <div class="form-container content-width-full global_region-form-content js-ak-delete-container">
        <form method="POST" action="@isset($data->id){{route("global_region.update", $data->id)}}@else{{route("global_region.store")}}@endisset" enctype="multipart/form-data" class="form-page validate-form" novalidate>
            <div hidden>
                @isset($data->id) @method('PUT') @endisset
                @csrf
            </div>
             <div class="form-header">
                <h3>{{ isset($data->id) ? 'Update' : 'Add New' }}</h3>
                @can('delete-GlobalRegion')
                <div class="form-delete-record">
                    @if(isset($data->id))
                        <a href="#" data-link="{{route('global_region.destroy',$data->id)}}" data-id="{{$data->id}}" class="delete-link js-ak-delete-link" draggable="false">
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
                <label for="global_region_name">Global Region Name <span class="required">*</span></label>
            </div>
            <div class="input-data">
                <input type="text" class="form-input " id="global_region_name" autocomplete="off"
                    name="global_region_name" placeholder="Global Region Name"
                    value="{{ old('global_region_name', $data->global_region_name ?? '') }}"   />
                <div class="error-message @if ($errors->has('global_region_name')) show @endif">
                    Required!</div>
                <div class="text-muted" id="global_region_name_help">
                    
                </div>
            </div>
        </div>
    </div>    <div class="row-50">
        <div class="input-container">
            <div class="input-label">
                <label for="global_region_code">Global Region Code <span class="required">*</span></label>
            </div>
            <div class="input-data">
                <input type="text" class="form-input " id="global_region_code" autocomplete="off"
                    name="global_region_code" placeholder="Global Region Code"
                    value="{{ old('global_region_code', $data->global_region_code ?? '') }}"   />
                <div class="error-message @if ($errors->has('global_region_code')) show @endif">
                    Required!</div>
                <div class="text-muted" id="global_region_code_help">
                    
                </div>
            </div>
        </div>
    </div>
            </div>
            @includeIf("layouts.form_footer",["cancel_route"=>route("global_region.index")])
        </form>

    </div>
    @isset($data->id)
        @includeIf("layouts.delete_modal_confirm")
    @endisset
@endsection
