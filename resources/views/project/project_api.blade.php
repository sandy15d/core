@extends('layouts.app')
@push('breadcrumb')
    <li class="breadcrumb-item active">Project</li>
    <li class="breadcrumb-item active">Set API</li>
@endpush
@push('bottom_style')
    <!-- multi.js css -->
    <link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/assets/vendors/multi.js/multi.min.css"/>
    <style>

    </style>
@endpush

@section('content')
    <div class="page-content-width-full">
        <div
            class="content-layout content-width-full  js-ak-DataTable js-ak-delete-container js-ak-content-layout"

            data-delete-modal-action="">
            <div class="content-element">
                <div class="content-header">
                    <div class="header">
                        <h3>Project API List</h3>
                    </div>
                    <div class="action">
                        <div class="left">
                        </div>
                        <div class="right">
                            <button class="button primary-button add-new"
                                    draggable="false">
                                @includeIf("layouts.icons.save")
                                Save
                            </button>
                        </div>
                    </div>
                </div>
                <div class="content">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mt-4">
                                <b><p class="danger-text">
                                        Click on the API in the list to select it for project.</p></b>
                                <form>
                                    <input type="hidden" name="project_id" id="project_id" value="{{$projectId}}">
                                    <select id="apis" name="apis[]" multiple>
                                        @foreach ($apiList as $model => $apis)
                                            <optgroup label="{{ $model }}">
                                                @foreach ($apis as $api)
                                                    <option value="{{ $api->id }}"
                                                        {{ in_array($api->id, $projectApis) ? 'selected' : '' }}>
                                                        {{ $api->api_name }}
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                </form>
                            </div>
                        </div>
                        <!-- end col -->
                    </div>
                    <!-- end row -->
                </div>


            </div>
        </div>


    </div>
@endsection

@push('bottom_script')
    <!-- multi.js -->
    <script src="{{URL::to('/')}}/assets/vendors/multi.js/multi.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const multiSelectOptGroup = document.getElementById("apis");
            if (multiSelectOptGroup) {
                multi(multiSelectOptGroup, {enable_search: true});
            }
        });

        $(document).on('click', '.add-new', function () {
            const project_id = $("#project_id").val();
            const apis = $("#apis").val();
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.post({
                url: "{{url('set_api')}}",
                data: {
                    project_id,
                    apis,
                },
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success(response) {
                    const message = response.status === 400
                        ? 'Something went wrong.. Please try again'
                        : 'API Mapped Successfully to Project.';

                    const toastType = response.status === 400 ? 'error' : 'success';
                    ToastStart[toastType](message);

                    setTimeout(() => location.reload(true), 1000);
                }
            });
        });

    </script>

@endpush
