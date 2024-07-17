@extends('layouts.app')
@push('breadcrumb')
    <li class="breadcrumb-item active">Permission</li>
@endpush
@push('page-title')
    Permission
@endpush
@push('bottom_style')
    <style>
        /* Basic styles for the badge */
        .badge {
            display: inline-block;
            padding: 0.25em 0.4em;
            font-size: 75%;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.375rem;
        }

        /* Primary badge styles */
        .badge-primary {
            color: #fff;
            background-color: rgb(122 91 121);
        }

        /* Hover and focus states */
        .badge-primary:hover,
        .badge-primary:focus {
            background-color: rgb(54 39 54);
        }

        /* Optional: Styles for rounded pill badges */
        .badge.rounded-pill {
            border-radius: 10rem;
        }

    </style>
@endpush

@section('content')
    <div class="page-content-width-full">
        <div
            class="content-layout content-width-full permission-data-content js-ak-DataTable js-ak-delete-container js-ak-content-layout"
            data-id="permission"
            data-delete-modal-action="">
            <div class="content-element">
                <div class="content-header">
                    <div class="header">
                        <h3>Permission List</h3>
                    </div>
                </div>
                <div class="content table-content">
                    <table class="table">
                        @foreach($permissions as $permission => $value)
                            <tr>
                                <th>{{ Str::ucfirst($permission) }}</th>
                                <td style="white-space: normal">
                                    @foreach($value as $v)
                                        <span class="badge badge-primary">{{ $v }}</span>
                                    @endforeach
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection
