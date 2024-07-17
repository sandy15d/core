<script>

    let dataTable_no_records = 'No Records!';
    let dataTable_table_info = '_START_ to _END_ of total: _TOTAL_';
    let lengthMenu = [[10, 50, 100, -1], [10, 50, 100, "All"]];
    let success_upload_message = 'Successfully uploaded.';
    let server_side_error_message = 'Server side error';
    let flatPickerSettings = {
        locale: {
            weekdays: {
                shorthand: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
                longhand: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
            },
            months: {
                shorthand: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                longhand: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
            },
            firstDayOfWeek: 1,
            weekAbbreviation: "Sun.",
            rangeSeparator: " - ",
            time_24hr: false,
            show_months: 1,
            enableSeconds: false,
        },
        rangeSeparator: " - ",
        date_format: "F j, Y",
        date_time_format: "F j, Y H:i",
        time_format: "H:i",
    };
    let widget_lang = {
        total: "Total",
        loading: "Loading...",
        no_results: "No records.",
    };
</script>


<script src="{{URL::to('/')}}/assets/vendors/popper.min.js"></script>
<script src="{{URL::to('/')}}/assets/vendors/jquery/jquery.min.js"></script>
<script src="{{URL::to('/')}}/assets/vendors/jquery-ui/jquery-ui.min.js"></script>
<script src="{{URL::to('/')}}/assets/vendors/moment/moment.js"></script>
<script src="{{URL::to('/')}}/assets/vendors/datetime-flatpickr/flatpickr.js"></script>
<script src="{{URL::to('/')}}/assets/vendors/listjs/list.min.js"></script>
<script src="{{URL::to('/')}}/assets/vendors/select2/select2.full.js"></script>
<script src="{{URL::to('/')}}/assets/vendors/tinymce/tinymce.min.js"></script>
<script src="{{URL::to('/')}}/assets/vendors/dropzone/dropzone-min.js"></script>
<script src="{{URL::to('/')}}/assets/vendors/data-tables/datatables.min.js"></script>
<script src="{{URL::to('/')}}/assets/vendors/cropperjs/cropper.min.js"></script>
<script src="{{URL::to('/')}}/assets/vendors/apexcharts/apexcharts.min.js"></script>


<script src="{{URL::to('/')}}/assets/js/base/ak_global.js"></script>
<script src="{{URL::to('/')}}/assets/js/base/ak_delete.js"></script>
<script src="{{URL::to('/')}}/assets/js/base/ak_dataTables.js"></script>
<script src="{{URL::to('/')}}/assets/js/base/ak_use_ajax.js"></script>
<script src="{{URL::to('/')}}/assets/js/base/ak_list_js.js"></script>
<script src="{{URL::to('/')}}/assets/js/base/ak_date_time.js"></script>
<script src="{{URL::to('/')}}/assets/js/base/ak_text_editor.js"></script>
<script src="{{URL::to('/')}}/assets/js/base/ak_numbers.js"></script>
<script src="{{URL::to('/')}}/assets/js/base/ak_select2.js"></script>
<script src="{{URL::to('/')}}/assets/js/base/ak_image.js"></script>
<script src="{{URL::to('/')}}/assets/js/base/ak_range.js"></script>
<script src="{{URL::to('/')}}/assets/js/base/ak_checkbox.js"></script>
<script src="{{URL::to('/')}}/assets/js/base/ak_drag_drop.js"></script>
<script src="{{URL::to('/')}}/assets/js/base/ak_dropzone.js"></script>
<script src="{{URL::to('/')}}/assets/js/base/ak_toast.js"></script>
<script src="{{URL::to('/')}}/assets/js/base/ak_form_validate.js"></script>
<script src="{{URL::to('/')}}/assets/js/base/ak_maps.js"></script>
<script src="{{URL::to('/')}}/assets/js/base/ak_cropper_js.js"></script>
<script src="{{URL::to('/')}}/assets/js/base/ak_print.js"></script>
<script src="{{URL::to('/')}}/assets/js/base/ak_apexcharts.js"></script>

<script src="{{URL::to('/')}}/assets/js/custom/global.js"></script>
<script src="{{URL::to('/')}}/assets/js/custom/delete.js"></script>
<script src="{{URL::to('/')}}/assets/js/custom/dataTables.js"></script>
<script src="{{URL::to('/')}}/assets/js/custom/use_ajax.js"></script>
<script src="{{URL::to('/')}}/assets/js/custom/list_js.js"></script>
<script src="{{URL::to('/')}}/assets/js/custom/date_time.js"></script>
<script src="{{URL::to('/')}}/assets/js/custom/text_editor.js"></script>
<script src="{{URL::to('/')}}/assets/js/custom/numbers.js"></script>
<script src="{{URL::to('/')}}/assets/js/custom/select2.js"></script>
<script src="{{URL::to('/')}}/assets/js/custom/image.js"></script>
<script src="{{URL::to('/')}}/assets/js/custom/range.js"></script>
<script src="{{URL::to('/')}}/assets/js/custom/checkbox.js"></script>
<script src="{{URL::to('/')}}/assets/js/custom/drag_drop.js"></script>
<script src="{{URL::to('/')}}/assets/js/custom/dropzone.js"></script>
<script src="{{URL::to('/')}}/assets/js/custom/toast.js"></script>
<script src="{{URL::to('/')}}/assets/js/custom/form_validate.js"></script>
<script src="{{URL::to('/')}}/assets/js/custom/maps.js"></script>
<script src="{{URL::to('/')}}/assets/js/custom/cropper_js.js"></script>
<script src="{{URL::to('/')}}/assets/js/custom/print.js"></script>
<script src="{{URL::to('/')}}/assets/js/custom/apexcharts.js"></script>
