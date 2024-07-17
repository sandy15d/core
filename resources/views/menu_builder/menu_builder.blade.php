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
    <link rel="stylesheet" href="{{URL::to('/')}}/assets/css/jquery-nestable.css">
@endpush
@section('content')
    <div class="page-content-width-full">
        <div class="content-layout content-width-full">
            <div class="content-element">
                <div class="content-header">
                    <div class="action">
                        <div class="left">

                        </div>
                        <div class="right">
                            <a href="javascript:void(0);" class="button primary-button add-new"
                               draggable="false" data-bs-toggle="modal" data-bs-target="#menuModal">
                                @includeIf("layouts.icons.add_new_icon")
                                Add New
                            </a>
                        </div>
                    </div>
                </div>
                <div class="content">
                    <button class="btn primary-button btn-sm custom-toggle"  id="nestable-button"
                            data-action="expand-all">
                       Expand All
                    </button>

                    <button href="javascript:void(0);" class="btn primary-button btn-sm"
                       onclick="set_menu_position()">
                        Save All
                    </button>

                    <div class="dd" id="nestable">
                        <?php
                        $menu = displayNestableMenu(0, 1, 1, 1, 1);
                        if (empty($menu)): ?>
                        <div class="box-no-data">No Record Found!</div>
                        <?php else:
                            echo $menu;
                        endif; ?>
                    </div>
                    <div class="nestable-output"></div>
                </div>

            </div>
        </div>

        <div id="menuModal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
             data-bs-keyboard="false">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                id="close-modal">X</button>
                    </div>
                    <form action="{{'menu-builder.store'}}" method="POST" id="menu_form">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Parent Menu</label>
                                    <div class="mb-2">
                                        <select name="parent_id" id="parent_id"
                                                class="form-select  font-weight-bold ">
                                            <option value="0">Set as Parent Menu</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Menu Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-input font-weight-semibold "
                                           name="menu_name" id="menu_name" placeholder="Enter Menu Name" required>
                                    <input type="hidden" name="id" id="id">
                                </div>


{{--                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Icon</label>
                                    --}}{{--<input type="text" class="form-input font-weight-semibold "
                                           name="menu_icon" id="menu_icon" placeholder="Enter Menu Icon">--}}{{--
                                    <select name="menu_icon" id="menu_icon" class="form-select font-weight-bold">
                                        @foreach($icon_list as $icon)
                                            <option value="ri-{{$icon}}-line">{{ucfirst(str_replace('-', ' ', $icon))}}</option>
                                        @endforeach
                                    </select>
                                </div>--}}

                                <div class="col-md-6 mb-2">
                                    <label class="form-label">URL</label>
                                    <input type="text" class="form-input font-weight-semibold "
                                           name="menu_url" id="menu_url" placeholder="Enter Menu Url">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Position <span class="text-danger">*</span></label>
                                    <input type="number" min="0" class="form-input"
                                           name="menu_position" id="menu_position" placeholder="Enter Position"
                                           required>
                                </div>

                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Status</label>
                                    <select class="form-select font-weight-bold" name="menu_status"
                                            id="menu_status">
                                        <option value="A">Active</option>
                                        <option value="D">In-Active</option>
                                    </select>
                                </div>
                            </div>


                        </div>
                        <div class="modal-footer">
                            <button type="button" class="button danger-button" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="button primary-button">Save</button>
                        </div>
                    </form>


                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </div>
@endsection
@push('bottom_script')
    <script src="{{URL::to('/')}}/assets/js/jquery-nestable.js"></script>
    <script>
        $(document).ready(function () {
            $.ajaxSetup({
                data: {
                    "_token": "{{ csrf_token() }}",
                }
            });

            $(".select2").select2({
                placeholder: function () {
                    $(this).data('placeholder');
                }
            });

            $('#nestable').nestable({
                group: 1
            });

            $('.dd').nestable('collapseAll');




            $('#menu_form').on('submit', function (e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('menu-builder.store') }}",
                    method: 'POST',
                    dataType: 'json',
                    data: $(this).serialize(),
                    success: function (data) {
                        if (data.status) {

                            alert(data.message)
                            location.reload();
                        } else {
                            alert(data.message)

                        }
                    },
                    error: function (data) {
                        alert('something went wrong!')

                    }
                });

            });
        });

        $('#nestable-button').on('click', function (e) {
            var target = $(e.target),
                action = target.data('action');
            if (action === 'expand-all') {
                $('.dd').nestable('expandAll');
                target.data('action', 'collapse-all').html('Collapse All')
            }
            if (action === 'collapse-all') {
                $('.dd').nestable('collapseAll');
                target.data('action', 'expand-all').html('Expand All');
            }
        });
        function set_menu_position() {
            var menu = $('.dd').nestable('serialize');
            if ($.trim(menu)) {

                $.ajax({
                    url: "{{ route('menu-builder.setPosition') }}",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        'menu': menu
                    },
                    success: function (data) {
                        if (data.status) {
                            alert('Menu Updated Successfully !');
                        } else {
                            alert('Menu Not Updated !');
                        }

                    },
                    error: function (data) {
                        alert('something went wrong!');

                    }
                });
            }
        }

        function get_form(id = 0) {
            $("#menu_form").trigger("reset");

            $("#id").val(id);

            if (id) {

                $("#menu_modal_label").html("Edit Menu");
                $("#menu_modal_button").html('<b><i class="icon-stack"></i></b>Update');
                $.ajax({
                    url: "{{ route('menu-builder.show_menu') }}",
                    method: 'POST',
                    dataType: 'json',
                    data: {id: id},
                    success: function (data) {
                        $("#menu_name").val(data.menu_name);
                        $("#menu_icon").val(data.menu_icon);
                        $("#menu_url").val(data.menu_url);
                        $("#menu_position").val(data.menu_position);
                        $("#menu_status").val(data.status).trigger('change');
                        get_menu_parent_list(id, data.parent_id)
                        $("#menuModal").modal('show');
                    },
                    error: function (data) {
                        alert('something went wrong!');
                    }
                });
            } else {
                $("#menu_modal_label").html("Add Menu");
                $("#menu_modal_button").html('<b><i class="icon-stack"></i></b>Submit');
                get_menu_parent_list(id)
                $("#menu_modal").modal('show');
            }
        }

        function get_menu_parent_list(id, parent_id = 0) {

            var innehtml = '<option value="0">Set as Parent Menu</option>';

            $.ajax({
                url: "{{ route('menu-builder.getParentMenus') }}",
                method: 'POST',
                dataType: 'json',
                data: {id: id},
                success: function (data) {
                    if (data.status) {
                        var result = data.result;
                        for (var j = 0; j < result.length; j++) {
                            if (parent_id == result[j].id) {
                                innehtml += '<option value ="' + result[j].id + '" selected>' + result[j].menu_name + '</option>';
                            } else {
                                innehtml += '<option value ="' + result[j].id + '">' + result[j].menu_name + '</option>';
                            }
                        }
                    }
                    $('#parent_id').html(innehtml).trigger('change');

                },
                error: function (data) {
                    $('#parent_id').html(innehtml);
                }
            });
        }


    </script>
@endpush
