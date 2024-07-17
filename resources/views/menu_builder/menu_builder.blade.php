@extends('layouts.app')
@push('breadcrumb')
    <li class="breadcrumb-item active">Menu Builder</li>
@endpush
@push('page-title')
    Menu Builder
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
@section('content')
    <div class="page-content-width-full">
        <div class="content-layout content-width-full">
            <div class="content-element">
                <div class="content-header">
                    {{--<div class="action">
                        <div class="left">

                        </div>
                        <div class="right">
                            <a href="javascript:void(0);" class="button primary-button add-new"
                               draggable="false" data-bs-toggle="modal" data-bs-target="#menuModal">
                                @includeIf("layouts.icons.add_new_icon")
                                Add New
                            </a>
                        </div>
                    </div>--}}
                </div>
                <div class="content">
                  Under Construction
                </div>

            </div>
        </div>

        <div id="menuModal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
             data-bs-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        Create New Container Menu
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                id="close-modal">X
                        </button>
                    </div>
                    <form action="" method="POST" id="menu_form">
                        @csrf
                        <div class="modal-body">
                            <div class="row-100 el-box-date">
                                <div class="input-container">
                                    <div class="input-label">
                                        <label for="menu_name">Menu</label>
                                    </div>
                                    <div class="input-data">
                                        <input type="text" name="menu_name" autocomplete="off"
                                               class="form-input"
                                               id="menu_name" placeholder="Menu Name">
                                        <div class="error-message @if ($errors->has('menu_name')) show @endif">
                                            Required
                                        </div>
                                        <div class="text-muted" id="menu_name_help"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="button primary-button">Save</button>
                            <button type="button" class="button danger-button" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>


                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </div>
@endsection
