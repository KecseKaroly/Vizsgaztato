@extends('layouts.app')
@section('content')
<div class="mt-24 mb-24">
    <div class="flex flex-col max-w-full mx-auto rounded-xl overflow-hidden  lg:w-4/6 md:w-8/12 sm:w-11/12 w-11/12">
        <div class="flex">
            <div class="text-center mb-12 font-black text-3xl w-full">Meghívások csoportokba</div>
        </div>
        <div class="bg-slate-50 w-11/12 rounded-xl">
            @foreach($inv_requests as $inv_request)
                <div id="request-{{ $inv_request->id }}">
                    <div>{{ $inv_request->USERNAME }}</div>
                    <div>
                        <form method="POST">
                            @csrf
                            <button type="submit" data-id="{{ $inv_request->id }}" data-invited_id="{{ $inv_request->invited_id }}" data-sender_id="{{ $inv_request->sender_id }}" data-group_id="{{ $inv_request->group_id }}" class="acceptInvRequest">Elfogad</button>
                        </form>
                    </div>
                    <div>
                        <form method="POST">
                            @csrf
                            <button type="submit" data-id="{{ $inv_request->id }}" class="declineInvRequest">Elutasít</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
