@extends('layouts.app')
@section('title', 'Csatlakozási kérelmek')
@section('content')
    <div class="flash-message"></div>
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
