@extends('layouts.app')
@push('breadcrumb')
    <li class="breadcrumb-item active">Page Builder</li>
@endpush
@push('page-title')

@endpush

@section('content')
    <div class="form-container content-width-medium page-form-content js-ak-delete-container">
        <form method="POST"
              action="@isset($data->id){{route("page-builder.update", $data->id)}}@else{{route("page-builder.store")}}@endisset"
              enctype="multipart/form-data" class="form-page validate-form" novalidate>
            <div hidden>
                @isset($data->id)
                    @method('PUT')
                @endisset
                @csrf
            </div>
            <div class="form-header">
                <h3>{{ isset($data->id) ? 'Update' : 'Add New' }}</h3>
                <div class="form-delete-record">
                    @if(isset($data->id))
                        <a href="#" data-link="{{route('page-builder.destroy',$data->id)}}" data-id="{{$data->id}}" class="delete-link js-ak-delete-link" draggable="false">
                            @includeIf("layouts.icons.delete_icon")
                        </a>
                    @endIf
                </div>
            </div>
            @includeIf("layouts.errors")
            <div class="form-content">
                <div class="row-100 el-box-text">
                    <div class="input-container">
                        <div class="input-label">
                            <label for="page_name">Page Name</label>
                        </div>
                        <div class="input-data">
                            <input type="text" class="form-input" id="page_name" autocomplete="off"
                                   name="page_name" placeholder="Page Name"
                                   value="{{{ old('page_name', $data->page_name??'') }}}">
                            <div class="error-message @if ($errors->has('page_name')) show @endif">Required!</div>
                            <div class="text-muted" id="page_name_help"></div>
                        </div>
                    </div>
                </div>
            </div>
            @includeIf("layouts.form_footer",["cancel_route"=>route("page-builder.index")])
        </form>
    </div>
    @isset($data->id)
        @includeIf("layouts.delete_modal_confirm")
    @endisset
@endsection
