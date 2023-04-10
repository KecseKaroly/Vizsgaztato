@extends('layouts.app')
@section('title', 'Teszt kitöltése')
@section('style')
    <style>
        div:has(>input:checked) {
            border: 5px solid blue;
        }
    </style>
@endsection
@section('scripts')
    @vite('resources/js/preventCheatScript.js')
@endsection
@section('content')
    <div class="testDiv border-4 border-red-600">
        @livewire('exam-task-write', ['testLiveWire' => $testLiveWire, 'course'=>$course])
    </div>
    <div class="testDiv fixed p-3 bg-slate-800 bottom-2 right-2 border rounded-lg text-stone-100 text-xl">
        <div class="flex text-center items-center">
            <i class="fa-solid fa-clock mr-2"></i>
            <div id="remainingHours">--:</div>
            <div id="remainingMinutes">--:</div>
            <div id="remainingSeconds">--</div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const countDownClock = (seconds) => {
            const remainingHours = document.getElementById('remainingHours');
            const remainingMinutes = document.getElementById('remainingMinutes');
            const remainingSeconds = document.getElementById('remainingSeconds');
            let countdown;
            return timer(seconds);

            function timer(seconds) {
                const now = Date.now();
                const then = Date.parse(@json($testLiveWire['started']));
                const secondsForTest = (@json($testLiveWire['duration']));

                countdown = setInterval(() => {
                    var secondsPast = Math.round((Date.now() - then) / 1000);
                    const secondsLeft = secondsForTest - secondsPast;
                    if (secondsLeft <= 0) {
                        clearInterval(countdown);
                        remainingSeconds.textContent = `0`;
                        // calling a function which is located in the LiveWire component
                        endTest();
                        return;
                    }
                    displayTimeLeft(secondsLeft);
                }, 1000);
            }

            function displayTimeLeft(secondsLeft) {
                remainingHours.textContent = `${("0" + Math.floor((secondsLeft / 3600))).slice(-2)}:`;
                remainingMinutes.textContent = `${("0" + Math.floor((secondsLeft % 3600) / 60)).slice(-2)}:`;
                remainingSeconds.textContent = `${("0" + secondsLeft % 60).slice(-2)}`;
            }
        }
        countDownClock(@json($testLiveWire['duration']));
    </script>
@endpush
