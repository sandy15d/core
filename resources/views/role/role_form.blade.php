@extends('layouts.app')
@push('breadcrumb')
    <li class="breadcrumb-item">Form</li>
    <li class="breadcrumb-item active"> Role</li>
@endpush
@push('page-title')
    Role
@endpush
@push('upper_style')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
          crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous">
    </script>
@endpush
@push('page-back-button')
    <div class="page-back-button">
        <a href="{{ route("role.index") }}">
            <div class="icon">
                <div class="font-awesome-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512">
                        <path
                            d="M192 448c-8.188 0-16.38-3.125-22.62-9.375l-160-160c-12.5-12.5-12.5-32.75 0-45.25l160-160c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25L77.25 256l137.4 137.4c12.5 12.5 12.5 32.75 0 45.25C208.4 444.9 200.2 448 192 448z"/>
                    </svg>
                </div>
            </div>
            <div>Back</div>
        </a>
    </div>
@endpush
@section('content')
    <div class="form-container content-width-full role-form-content js-ak-delete-container">
        <form action="/role/{{$role->id}}" method="POST" id="updateForm">
            @csrf
            @method('PUT')

            <div class="row mb-3 mt-3" style="margin-left: 10px;margin-right: 10px;">
                <div class="col-md-4">
                    <label for="role_name">Role Name :</label> <span
                        class="text-danger">*</span>
                    <input type="text" name="role_name" id="role_name" class="form-input"
                           value="{{$role->name}}">
                    <span class="text-danger error-text role_name_error"></span>
                </div>
            </div>
            <hr class="mb-3">

            @foreach($permissions as $permission => $value)
                <div class="row mb-3" style="margin-left: 10px;margin-right: 10px;">
                    <div class="col-lg-12">
                        <h6 style="width:280px; height: 40px;padding: 10px; border-radius: 0px 20px 0px 0px;background-color: #7a5b79;color: #fff;line-height: 25px;"
                            class="fw-semibold bg-light-success border-bottom  mb-0">
                            <span class=""><input type="checkbox" class="form-check-input {{$permission}}" onclick="checkAllPermissin('{{$permission}}');"> </span>{{ mb_strtoupper($permission) }}
                        </h6>
                        <div class="card-body" style="background-color: #f3f3f3;padding: 8px;border-bottom: 1px solid #ddd;">
                            <div class="row">
                                @foreach($value as $k => $v)
                                    <div class="col-3">
                                        <div class="form-check">
                                            <input type="checkbox" name="permission[]"
                                                   class="form-check-input {{$permission}}"
                                                   id="{{$v['id']}}"
                                                   value="{{$v['id']}}" {{in_array($v['id'],$rolePermissions)?'checked':''}}>
                                            <label class="form-check-label"
                                                   for="{{$v['id']}}">{{$v['name']}}</label>
                                        </div>
                                    </div>

                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="modal-footer" style="margin-right: 20px;margin-bottom: 20px;">
                <button type="submit" class="button primary-button"><i
                        class="bx bx-save font-size-16 align-middle"></i> Update
                </button>
            </div>
        </form>

    </div>
    @isset($data->id)
        @includeIf("layouts.delete_modal_confirm")
    @endisset
@endsection
@push('bottom_script')
    <script>
        function checkAllPermissin(permission) {
            $('.' + permission).prop('checked', $('.' + permission + ':first').prop('checked'));
        }
    </script>
@endpush
