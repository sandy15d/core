@extends('layouts.app')
@push('breadcrumb')
    <li class="breadcrumb-item active">Project Builder</li>
@endpush
@push('page-title')
    Project List
@endpush
@push('bottom_style')
    <style>
        .ak-project-container {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 1rem;

        }

        .ak-project-container .ak-project-content {
            --tw-bg-opacity: 1;
            --tw-shadow: 0 4px 6px -1px rgba(0, 0, 0, .1), 0 2px 4px -2px rgba(0, 0, 0, .1);
            background-color: rgb(255 255 255 / var(--tw-bg-opacity));
            border-radius: .5rem;
            box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow);
            padding: 1rem .5rem 1.5rem;

            max-width: 300px;
            min-height: 100px;

            position: relative;
            width: 100%;
        }

        .ak-project-container .ak-project-content .settings {
            padding-right: .5rem;
            padding-top: .5rem;
            position: absolute;
            right: 0;
            top: 0;
        }

        .ak-project-container .ak-project-content .ak-project-info {
            align-items: center;
            display: flex;
            flex-direction: column;
        }

        .builder {
            display: flex;
            margin-top: 1rem;
        }

        .ak-project-container .ak-project-content .ak-project-info span:first-child {
            font-size: .75rem;
            line-height: 1rem;
            padding-bottom: .25rem;
        }

        /* Ribbon CSS */
        /* Smaller Ribbon CSS */
        .ribbon {
            width: 75px;
            height: 75px;
            overflow: hidden;
            position: absolute;
            top: -5px;
            left: -5px;
        }

        .ribbon span {
            position: absolute;
            display: block;
            width: 100px;
            padding: 5px 0;
            background-color: palevioletred;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            color: #fff;
            font: 700 12px/1 'Lato', sans-serif;
            text-transform: uppercase;
            text-align: center;
            left: -20px;
            top: 20px;
            transform: rotate(-45deg);
        }
    </style>
@endpush
@section('content')
    <div class="page-content-width-full">

        <div style="width: 150px;">
            <a class="button primary-button" href="{{route('project.create')}}">
                @includeIf("layouts.icons.add_new_icon") New Project
            </a>
        </div>


        <div class="ak-project-container">
            @foreach($projects as $project)
                <div class="ak-project-content">
                    @if($project->is_active == 0)
                        <div class="ribbon"><span>Deactive</span></div>
                    @endif
                    <div class="settings">
                        <a draggable="false" class="" href="{{route('project.edit',$project->id)}}"><i
                                class="fa-solid fa-sliders"></i></a>
                    </div>
                    <div class="ak-project-info">
                        <span> {{ \Carbon\Carbon::parse($project->updated_at)->format('d M Y') }}</span>
                        <h5>{{$project->project_name}}</h5>
                        <div class="builder">
                            <a draggable="false" href=""
                               class="button primary-button"> <i
                                    class="fa-solid fa-layer-group pr-3"></i> Builder</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>


    </div>
@endsection
