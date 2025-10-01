@extends('layout.app', [
    'includeHeader' => true,
    'pageTitle' => 'Absage bestätigen (' . $shift->title . ')',
])

@section('body')
    <div class="max-w-full mx-2 mb-4 flex-1">
        {{-- Header + Intro-Text --}}
        <div class="w-full mb-6 md:mb-12 md:max-w-1/2 md:mx-auto md:text-center">
            <h1 class="section-header mb-2">{{ __('subscription.removeConfirm.title') }}</h1>
            <p class="text-sm md:text-base mb-2">{{ __('subscription.removeConfirm.intro') }}</p>
        </div>

        <div class="mb-6 md:mb-12">
            <p class="md:text-lg md:text-center font-bold">{{ $shift->title }}</p>
            <p class="text-sm md:text-base italic md:text-center">({{ $plan->title }})</p>
            <p class="text-sm md:text-lg md:text-center">{!! \App\Http\Controllers\PlanController::buildDateString($shift->start, $shift->end, true) !!}</p>
        </div>

        <div class="w-full mb-6 md:mb-12 md:max-w-1/2 md:mx-auto md:text-center">
            <form method="post" action="{{route('plan.subscription.doConfirmRemove', [$plan, $shift,$confirmation])}}">
                @csrf

                <button type="submit" class="icon-button big-button">
                    <span>{{__('subscription.unsubscribeConfirm')}}</span>
                    @include('partials.svg.frown')
                </button>
            </form>
        </div>
    </div>
@endsection
