@extends('layouts.app')

@push('breadcrumb')
    <li class="breadcrumb-item">Form</li>
    <li class="breadcrumb-item active">Company Address</li>
@endpush

@push('page-back-button')
    <div class="page-back-button">
        <a href="{{ route('company_address.index') }}">
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
    <div class="form-container content-width-full company_address-form-content js-ak-delete-container">
        <form method="POST"
              action="@isset($data->id){{ route('company_address.update', $data->id) }}@else{{ route('company_address.store') }}@endisset"
              enctype="multipart/form-data" class="form-page validate-form" novalidate>
            @csrf
            @isset($data->id)
                @method('PUT')
            @endisset

            <div class="form-header">
                <h3>{{ isset($data->id) ? 'Update' : 'Add New' }}</h3>
                @can('delete-CompanyAddress')
                    @if(isset($data->id))
                        <div class="form-delete-record">
                            <a href="#" data-link="{{ route('company_address.destroy', $data->id) }}"
                               data-id="{{ $data->id }}" class="delete-link js-ak-delete-link" draggable="false">
                                @includeIf('layouts.icons.delete_icon')
                            </a>
                        </div>
                    @endif
                @endcan
            </div>

            @includeIf('layouts.errors')

            <div class="form-content">
                <div class="row-66">
                    <div class="input-container">
                        <div class="input-label">
                            <label for="company_id">Company <span class="required">*</span></label>
                        </div>
                        <div class="input-data">
                            <select name="company_id" id="company_id" class="form-select js-ak-select2">
                                <option value="">Select Company</option>
                                @foreach ($company_list as $list)
                                    <option value="{{ $list->id }}" {{ $data->company_id == $list->id ? 'selected' : '' }}>{{ $list->company_name }}</option>
                                @endforeach
                            </select>
                            <div class="error-message @if ($errors->has('company_id')) show @endif">Required!</div>
                        </div>
                    </div>
                </div>
                <!-- Address Type -->
                <div class="row-33">
                    <div class="input-container">
                        <div class="input-label">
                            <label for="address_type">Address Type <span class="required">*</span></label>
                        </div>
                        <div class="input-data">
                            <input type="text" class="form-input" id="address_type" name="address_type" placeholder="Address Type"
                                   value="{{ old('address_type', $data->address_type ?? '') }}" autocomplete="off"/>
                            <div class="error-message @if ($errors->has('address_type')) show @endif">Required!</div>
                        </div>
                    </div>
                </div>
                <!-- Address -->
                <div class="row-100">
                    <div class="input-container">
                        <div class="input-label">
                            <label for="address">Address <span class="required">*</span></label>
                        </div>
                        <div class="input-data">
                            <textarea class="form-input form-textarea js-ak-tiny-mce-simple-text-editor" id="address"
                                      name="address" data-height="250" placeholder="Address">{{ old('address', $data->address ?? '') }}</textarea>
                            <div class="error-message @if ($errors->has('address')) show @endif">Required!</div>
                        </div>
                    </div>
                </div>
                <!-- Country -->
                <div class="row-33">
                    <div class="input-container">
                        <div class="input-label">
                            <label for="country_id">Country <span class="required">*</span></label>
                        </div>
                        <div class="input-data">
                            <select name="country_id" id="country_id" class="form-select">
                                <option value="">Select Country</option>
                                @foreach ($country_list as $list)
                                    <option value="{{ $list->id }}" {{ $data->country_id == $list->id ? 'selected' : '' }}>{{ $list->country_name }}</option>
                                @endforeach
                            </select>
                            <div class="error-message @if ($errors->has('country_id')) show @endif">Required!</div>
                        </div>
                    </div>
                </div>
                <!-- State -->
                <div class="row-33">
                    <div class="input-container">
                        <div class="input-label">
                            <label for="state_id">State <span class="required">*</span></label>
                        </div>
                        <div class="input-data">
                            <select name="state_id" id="state_id" class="form-select js-ak-select2">
                                <option value="">Select State</option>
                            </select>
                            <div class="error-message @if ($errors->has('state_id')) show @endif">Required!</div>
                        </div>
                    </div>
                </div>
                <!-- District -->
                <div class="row-33">
                    <div class="input-container">
                        <div class="input-label">
                            <label for="district_id">District <span class="required">*</span></label>
                        </div>
                        <div class="input-data">
                            <select name="district_id" id="district_id" class="form-select js-ak-select2">
                                <option value="">Select District</option>
                            </select>
                            <div class="error-message @if ($errors->has('district_id')) show @endif">Required!</div>
                        </div>
                    </div>
                </div>
                <!-- City -->
                <div class="row-33">
                    <div class="input-container">
                        <div class="input-label">
                            <label for="city_id">City <span class="required">*</span></label>
                        </div>
                        <div class="input-data">
                            <select name="city_id" id="city_id" class="form-select js-ak-select2">
                                <option value="">Select City</option>
                            </select>
                            <div class="error-message @if ($errors->has('city_id')) show @endif">Required!</div>
                        </div>
                    </div>
                </div>
                <!-- Pin Code -->
                <div class="row-25">
                    <div class="input-container">
                        <div class="input-label">
                            <label for="pin_code">Pin Code <span class="required">*</span></label>
                        </div>
                        <div class="input-data">
                            <input type="text" class="form-input" id="pin_code" name="pin_code" placeholder="Pin Code"
                                   value="{{ old('pin_code', $data->pin_code ?? '') }}" autocomplete="off"/>
                            <div class="error-message @if ($errors->has('pin_code')) show @endif">Required!</div>
                        </div>
                    </div>
                </div>
            </div>

            @includeIf('layouts.form_footer', ['cancel_route' => route('company_address.index')])

        </form>

    </div>
    @isset($data->id)
        @includeIf('layouts.delete_modal_confirm')
    @endisset
@endsection

@push('bottom_script')
    <script>
        // Reusable function to load dropdown data
        function loadDropdownData(url, data, dropdownSelector, selectedId = null) {
            if (data) {
                $.ajax({
                    url: url,
                    type: "GET",
                    data: data,
                    dataType: "json",
                    async: false,
                    success: function (response) {
                        let dropdown = $(dropdownSelector);
                        dropdown.empty(); // Clear previous options

                        const listKey = Object.keys(response)[0];
                        dropdown.append($('<option>', { value: '', text: 'Select '}));

                        $.each(response[listKey], function (key, value) {
                            dropdown.append($('<option>', {
                                value: value,
                                text: key,
                                selected: value == selectedId
                            }));
                        });

                        dropdown.trigger("change.select2");
                    }
                });
            }
        }

        $(document).ready(function () {
            const countrySelector = '#country_id';
            const stateSelector = '#state_id';
            const districtSelector = '#district_id';
            const citySelector = '#city_id';

            // Populate State dropdown based on selected Country
            $(countrySelector).on('change', function () {
                loadDropdownData(
                    "{{ route('get_states_by_country') }}",
                    { country_id: $(this).val() },
                    stateSelector
                );
            });

            // Populate District dropdown based on selected State
            $(stateSelector).on('change', function () {
                loadDropdownData(
                    "{{ route('get_district_by_state') }}",
                    { state_id: $(this).val() },
                    districtSelector
                );
            });

            // Populate City dropdown based on selected District
            $(districtSelector).on('change', function () {
                loadDropdownData(
                    "{{ route('get_city_by_district') }}",
                    { district_id: $(this).val() },
                    citySelector
                );
            });

            // Preselect values if editing
            @if(isset($data))
            loadDropdownData(
                "{{ route('get_states_by_country') }}",
                { country_id: '{{ $data->country_id }}' },
                stateSelector,
                '{{ $data->state_id }}'
            );

            loadDropdownData(
                "{{ route('get_district_by_state') }}",
                { state_id: '{{ $data->state_id }}' },
                districtSelector,
                '{{ $data->district_id }}'
            );

            loadDropdownData(
                "{{ route('get_city_by_district') }}",
                { district_id: '{{ $data->district_id }}' },
                citySelector,
                '{{ $data->city_id }}'
            );
            @endif
        });
    </script>
@endpush
