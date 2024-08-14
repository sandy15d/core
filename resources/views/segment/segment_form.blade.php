@extends('layouts.app')
@push('breadcrumb')
    <li class="breadcrumb-item">Form</li>
    <li class="breadcrumb-item active"> Segment</li>
@endpush

@push('page-back-button')
    <div class="page-back-button">
        <a href="{{ route("segment.index") }}">
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
    <div class="form-container content-width-full segment-form-content js-ak-delete-container">
        <form method="POST" action="@isset($data->id){{route("segment.update", $data->id)}}@else{{route("segment.store")}}@endisset" enctype="multipart/form-data" class="form-page validate-form" novalidate>
            <div hidden>
                @isset($data->id) @method('PUT') @endisset
                @csrf
            </div>
             <div class="form-header">
                <h3>{{ isset($data->id) ? 'Update' : 'Add New' }}</h3>
                @can('delete-Segment')
                <div class="form-delete-record">
                    @if(isset($data->id))
                        <a href="#" data-link="{{route('segment.destroy',$data->id)}}" data-id="{{$data->id}}" class="delete-link js-ak-delete-link" draggable="false">
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
                <label for="segment">Segment </label>
            </div>
            <div class="input-data">
                <input type="text" class="form-input " id="segment" autocomplete="off"
                    name="segment" placeholder="Segment"
                    value="{{ old('segment', $data->segment ?? '') }}"   />
                <div class="error-message @if ($errors->has('segment')) show @endif">
                    Required!</div>
                <div class="text-muted" id="segment_help">
                    
                </div>
            </div>
        </div>
    </div>    <div class="row-50">
        <div class="input-container">
            <div class="input-label">
                <label for="segment_id">Segment ID </label>
            </div>
            <div class="input-data">
                <input type="number" class="form-input " id="segment_id" autocomplete="off"
                    name="segment_id" placeholder="Segment ID"
                    value="{{ old('segment_id', $data->segment_id ?? '') }}"   />
                <div class="error-message @if ($errors->has('segment_id')) show @endif">
                    Required!</div>
                <div class="text-muted" id="segment_id_help">
                    
                </div>
            </div>
        </div>
    </div>    <div class="row-50">
        <div class="input-container">
            <div class="input-label">
                <label for="created_on">Created on </label>
            </div>
            <div class="input-data">
                 <div class="group-input date-time-group" id="ak_date_group_created_on">
                    <input type="text" name="created_on"
                           autocomplete="off" id="created_on"
                           class="form-input js-ak-date-picker"
                           placeholder="Created on" value="{{ old('created_on', isset($data->created_on)?$data->getRawOriginal('created_on') : '') }}">
                    <div class="input-suffix js-ak-calendar-icon"
                         data-target="#created_on">
                        @includeIf("layouts.icons.calendar_icon")
                    </div>
                    <div class="error-message @if ($errors->has('created_on')) show @endif">
                        Required!</div>
                </div>
                <div class="text-muted" id="created_on_help"></div>
            </div>
        </div>
    </div>    <div class="row-50">
        <div class="input-container">
            <div class="input-label">
                <label for="active/inactive">Active/Inactive </label>
            </div>
            <div class="input-data">
             <div class="checkbox-input ">
                        <input type="hidden" name="active/inactive" value="0">
                        <input class="form-checkbox" type="checkbox" id="active/inactive" name="active/inactive" value="1"
                               @if(old("active/inactive") || ((isset($data->active/inactive)&&$data->active/inactive==1))) checked @endif >
                        <label class="form-check-label" for="active/inactive"></label>
             </div>
              <div class="text-muted" id="active/inactive_help"></div>
             </div>
        </div>
    </div>
            </div>
            @includeIf("layouts.form_footer",["cancel_route"=>route("segment.index")])
        </form>

    </div>
    @isset($data->id)
        @includeIf("layouts.delete_modal_confirm")
    @endisset
@endsection
