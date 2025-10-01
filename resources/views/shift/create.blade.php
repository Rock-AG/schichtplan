@php
    $mode = (\Illuminate\Support\Facades\Route::currentRouteName() == "plan.shift.edit") ? "edit" : "create";

    if ($mode == "edit") {
        $pageTitle = $shift->title . " (Bearbeiten)";
        $formAction = route('plan.shift.update', ['plan' => $plan, 'shift' => $shift]);
    } else {
        $pageTitle = "Neue Schicht erstellen";
        $formAction = route('plan.shift.store', ['plan' => $plan]);
    }
@endphp

@extends('layout.app', [
    'includeHeader' => true,
    'pageTitle' => $pageTitle,
])

@section('body')
    
    <div class="max-w-full mx-2 mb-4 flex-1">

        {{-- Header --}}
        <h1 class="section-header mb-2">{{ __("shift.heading_" . $mode) }}</h1>

        <form action="{{ $formAction }}" method="post">
            @csrf

            @if($mode == "edit")
                @method("put")
            @endif

            <input id="group" name="group" type="hidden" value="0">

            <div class="md:grid md:grid-cols-2 md:grid-rows-2 md:gap-2 mb-2">

                <div class="mb-2 md:mb-0">
                    <label for="title" class="block mb-1 text-sm md:text-base">{{__("shift.title")}}</label>
                    <input id="title" name="title" placeholder="{{__('shift.title')}}" type="text" class="@error('title') error @enderror w-full" value="{{ old('title', $shift->title) }}">
                    @error('title')
                        <div class="text-red-700 text-xs italic pl-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-2 md:mb-0 md:row-span-5">
                    <label for="description" class="block mb-1 text-sm md:text-base">{{__("shift.description")}}</label>
                    <textarea id="description" rows="5" name="description" placeholder="{{__('shift.description')}}" class="@error('description') error @enderror w-full field-sizing-content">{{old('description', $shift->description)}}</textarea>
                    @error('description')
                        <div class="text-red-700 text-xs italic pl-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="md:flex md:gap-2">
                    <div class="mb-2 md:mb-0 md:flex-1">
                        <label for="start" class="block mb-1 text-sm md:text-base">{{__("shift.startDesc")}}</label>
                        <input id="start" name="start" placeholder="{{__('shift.startDesc')}}" type="text" class="datepicker @error('start') error @enderror w-full" value="{{ old('start', $shift->start) }}">
                        @error('start')
                            <div class="text-red-700 text-xs italic pl-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-2 md:mb-0 md:flex-1">
                        <label for="end" class="block mb-1 text-sm md:text-base">{{__("shift.endDesc")}}</label>
                        <input id="end" name="end" placeholder="{{__('shift.endDesc')}}" type="text" class="datepicker @error('end') error @enderror w-full" value="{{ old('end', $shift->end) }}">
                        @error('end')
                            <div class="text-red-700 text-xs italic pl-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-2 md:mb-0">
                    <label for="team_size" class="block mb-1 text-sm md:text-base">{{__("shift.team_sizeDesc")}}</label>
                    <input id="team_size" name="team_size" placeholder="{{__('shift.team_sizeDesc')}}" type="number" class="@error('team_size') error @enderror w-full" value="{{ old('team_size', $shift->team_size) }}">
                    @error('team_size')
                        <div class="text-red-700 text-xs italic pl-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-2 md:mb-0">
                    <label for="type" class="block mb-1 text-sm md:text-base">{{__("shift.type")}}</label>
                    <input id="type" name="type" placeholder="{{__('shift.type')}}" type="text" class="@error('type') error @enderror w-full" value="{{ old('type', $shift->type) }}">
                    @error('type')
                        <div class="text-red-700 text-xs italic pl-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="">
                <button type="submit" class="icon-button">
                    <span>{{__('general.buttonSave')}}</span>
                    @include('partials.svg.save')
                </button>

                <a href="{{ route('plan.admin', $plan) }}" class="icon-button">
                    {{__('general.buttonCancel')}}
                    @include('partials.svg.x')
                </a>
            </div>
            
        </form>
    </div>

@endsection
