@extends('layouts.app')
@section('title', 'Csatlakozási kérelmek')
@section('content')
    <div id="successfulJoinRequest" class="hidden mx-auto mt-2 text-center flex p-4 mb-4 text-green-800 rounded-lg bg-green-50 border-green-800 lg:w-10/12 md:w-8/12 w-11/12">
        <span class="sr-only">Success</span>
        <div class="text-2xl flex divide-x-2">
            <div class="pr-4"><i class="fa-solid fa-check"></i></div>
            <div class="pl-4 text-xl font-medium" id="successMessage"></div>
        </div>
        <button type="button" class="ml-auto  bg-green-50 text-green-500 rounded-lg hover:bg-green-200 inline-flex px-2.5 py-1 hover:ring-green-900 hover:ring-2" data-dismiss-target="#successfulJoinRequest" aria-label="Close">
            <span class="sr-only">Bezár</span>
            <span><i class="fa-solid fa-xmark fa-xl"></i></span>
        </button>
    </div>

    <div id="failedJoinRequest" class="hidden mx-auto mt-2 text-center flex p-4 mb-4 text-red-800 rounded-lg bg-red-50 border-red-800 lg:w-10/12 md:w-8/12 w-11/12">
        <span class="sr-only">Success</span>
        <div class="text-2xl flex divide-x-2">
            <div class="pr-4"><i class="fa-solid fa-circle-exclamation"></i></div>
            <div class="pl-4 text-xl font-medium" id="failMessage"></div>
        </div>
        <button type="button" class="ml-auto  bg-red-50 text-red-500 rounded-lg hover:bg-red-200 inline-flex px-2.5 py-1 hover:ring-red-900 hover:red-2" data-dismiss-target="#failedJoinRequest" aria-label="Close">
            <span class="sr-only">Bezár</span>
            <span><i class="fa-solid fa-xmark fa-xl"></i></span>
        </button>
    </div>

    <div class="mt-24 mb-24">
    <div class="flex flex-col max-w-full mx-auto rounded-xl overflow-hidden  lg:w-4/6 md:w-8/12 sm:w-11/12 w-11/12">
        <div class="flex">
            <div class="text-center mb-12 font-black text-3xl w-full">Csoport csatlakozási kérelmei</div>
        </div>
        <div class="bg-slate-50 w-11/12 rounded-xl divide-y pb-3">
            <table class="table-auto w-full">
                <thead class="bg-slate-400">
                  <tr>
                    <th>Kérelmet küldte</th>
                    <th>Műveletek</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach($join_requests as $join_request)
                        <tr id="request-{{ $join_request->id }}" class="text-center border">
                            <td class="font-medium">{{ $join_request->name }}</td>
                            <td class="flex justify-evenly ">
                                <div class="text-4xl">
                                    <form method="POST">
                                        @csrf
                                        <input type="hidden" name="join_request_id" value="{{ $join_request->id }}">
                                        <input type="hidden" name="requester_id" value="{{ $join_request->requester_id }}">
                                        <button type="submit" data-id="{{ $join_request->id }}" data-requester_id="{{ $join_request->requester_id }}" data-group_id="{{ $join_request->group_id }}" class="acceptJoinRequest"><i class="fa-solid fa-circle-check" style="color: green;"></i></button>
                                    </form>
                                </div>
                                <div class="text-4xl">
                                    <form method="POST">
                                        @csrf
                                        <button type="submit" data-id="{{ $join_request->id }}" class="declineJoinRequest"><i class="fa-solid fa-circle-xmark" style="color: red;"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
              </table>

        </div>
    </div>
</div>

@endsection
