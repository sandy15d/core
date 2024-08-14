@extends('layouts.app')
@push('breadcrumb')
    <li class="breadcrumb-item">Form</li>
    <li class="breadcrumb-item active"> Employment Type</li>
@endpush

@push('page-back-button')
    <div class="page-back-button">
        <a href="{{ route("employment_type.index") }}">
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
    <div class="form-container content-width-full employment_type-form-content js-ak-delete-container">
        <form method="POST" action="@isset($data->id){{route("employment_type.update", $data->id)}}@else{{route("employment_type.store")}}@endisset" enctype="multipart/form-data" class="form-page validate-form" novalidate>
            <div hidden>
                @isset($data->id) @method('PUT') @endisset
                @csrf
            </div>
             <div class="form-header">
                <h3>{{ isset($data->id) ? 'Update' : 'Add New' }}</h3>
                @can('delete-EmploymentType')
                <div class="form-delete-record">
                    @if(isset($data->id))
                        <a href="#" data-link="{{route('employment_type.destroy',$data->id)}}" data-id="{{$data->id}}" class="delete-link js-ak-delete-link" draggable="false">
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
                <label for="employment_type">Employment Type </label>
            </div>
            <div class="input-data">
                <input type="text" class="form-input " id="employment_type" autocomplete="off"
                    name="employment_type" placeholder="Employment Type"
                    value="{{ old('employment_type', $data->employment_type ?? '') }}"   />
                <div class="error-message @if ($errors->has('employment_type')) show @endif">
                    Required!</div>
                <div class="text-muted" id="employment_type_help">
                    
                </div>
            </div>
        </div>
    </div>    <div class="row-50">
        <div class="input-container">
            <div class="input-label">
                <label for="emp_type_id">Emp Type Id </label>
            </div>
            <div class="input-data">
                <input type="number" class="form-input " id="emp_type_id" autocomplete="off"
                    name="emp_type_id" placeholder="Emp Type Id"
                    value="{{ old('emp_type_id', $data->emp_type_id ?? '') }}"   />
                <div class="error-message @if ($errors->has('emp_type_id')) show @endif">
                    Required!</div>
                <div class="text-muted" id="emp_type_id_help">
                    
                </div>
            </div>
        </div>
    </div>
            </div>
            @includeIf("layouts.form_footer",["cancel_route"=>route("employment_type.index")])
        </form>

    </div>
    @isset($data->id)
        @includeIf("layouts.delete_modal_confirm")
    @endisset
@endsection
