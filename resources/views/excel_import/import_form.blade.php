@extends('layouts.app')
@push('breadcrumb')
    <li class="breadcrumb-item active">Import</li>
@endpush
@push('page-title')
    Import Data
@endpush

@section('content')
    <section class="page-section">
        <div class="page-content js-ak-page-content">
            <div class="form-container content-width-full user-form-content js-ak-delete-container">
                @if(session('success'))
                    <div class="alert primary-text">{{ session('success') }}</div>
                @endif

                @if($errors->any())
                    <div class="danger-text">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method="POST" action="{{ route('import.upload') }}" enctype="multipart/form-data"
                      class="form-page validate-form" novalidate="">
                    @csrf

                    <div class="form-header">
                        <h3>Add New</h3>
                        <div class="form-delete-record">
                        </div>
                    </div>

                    <div class="form-content">
                        <div class="row-50">
                            <div class="input-container">
                                <label for="table_name">Select Table</label>
                            </div>
                            <div class="input-data">
                                <select name="table_name" id="table_name" class="form-select  js-ak-select2-many">
                                    <option value="">Select Table</option>
                                    @foreach($page_list as $table)
                                        <option value="{{ $table->snake_case }}">{{ $table->page_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row-50"></div>
                        <div class="row-50">
                            <div class="input-container">
                                <div class="input-label">
                                    <label for="file">File:</label>
                                </div>
                                <div class="input-data">
                                    <input type="file" name="file" id="file" class="form-file">
                                </div>
                            </div>
                        </div>
                        <div class="row-100" style="margin-top: 10px;">
                            <div id="column_names_container" class="form-group mt-3">

                            </div>
                        </div>
                    </div>
                    <div class="form-footer">
                        <div class="form-buttons-container">
                            <div>
                                <button type="submit" class="button primary-button submit-button js-ak-submit-button">
                                    Upload & Import
                                </button>
                            </div>
                            <div>
                                <a href="#" class="button cancel-button">Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </section>
@endsection
@push('bottom_script')
    <script>
        $(document).ready(function () {
            // When table is selected, fetch the column names
            $('#table_name').change(function () {
                const selectedTable = $(this).val();

                if (selectedTable) {
                    // Make an AJAX request to get the columns of the selected table
                    $.ajax({
                        url: '{{ route('import.columns') }}',
                        method: 'GET',
                        data: {table: selectedTable},
                        success: function (response) {
                            const container = $('#column_names_container');
                            container.empty(); // Clear the column container

                            // Check if there are columns to display
                            if (response.columns && response.columns.length > 0) {
                                // Create a table structure to display columns in a single row
                                let table = `<table class="table" style="width: 100%" border="1px">

                                    <tbody>
                                        <tr>`;

                                // Append each column name as a table cell
                                response.columns.forEach(function (column) {
                                    table += `<td>${column}</td>`;
                                });

                                table += `</tr>
                              </tbody>
                              </table>`;

                                // Append the table to the container
                                container.append('<p class="danger-text">Note: Please make sure below columns should be present in the file, which will be upload.</p>');
                                container.append(table);
                            } else {
                                container.html('<p>No columns found for the selected table.</p>');
                            }
                        },
                        error: function () {
                            $('#column_names_container').html('<p>Error fetching columns. Please try again.</p>');
                        }
                    });
                } else {
                    $('#column_names_container').empty();
                }
            });

        });
    </script>

@endpush
