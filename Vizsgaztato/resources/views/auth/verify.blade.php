@extends('layouts.app')

@section('title', 'Email megerősítése')
@section('content')
    <div id="verification-modal"  aria-hidden="true" class="z-50 p-4">
        <div class="mx-auto my-auto w-full h-full max-w-md md:h-auto">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="px-6 py-6 lg:px-8">
                    <div class="mb-4 text-xl font-medium text-gray-900 dark:text-white">Erősítse meg az Email címét!</div>
                    @if(session('resent'))
                        <div class="bg-green-200 text-green-500 mb-4">
                            Kiküldtük az email címére az új megerősítő levelet!
                        </div>
                    @endif

                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        Mielőtt folytatná, kérjük, ellenőrizze az email címére küldött megerősítő linket!
                        Amennyiben nem kapott ilyet,
                        <button type="submit" class="text-blue-600 hover:underline">{{ __('kattintson ide egy új igényléséhez') }}</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
