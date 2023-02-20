@extends('layouts.app')
@section('title', 'Meghívók')
@section('content')
<div class="mt-24 mb-24">
    <div class="flex flex-col max-w-full mx-auto rounded-xl overflow-hidden  lg:w-4/6 md:w-8/12 sm:w-11/12 w-11/12">
        <div class="flex">
            <div class="text-center mb-12 font-black text-3xl w-full">Meghívások csoportokba</div>
        </div>
        <div class="bg-slate-50 w-full rounded-xl divide-y pb-3">
            <table class="table-auto w-full">
                <thead class="bg-slate-400">
                  <tr>
                    <th>Csoport</th>
                    <th>Hívó</th>
                    <th>Műveletek</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach($inv_requests as $inv_request)
                    <tr id="request-{{ $inv_request->id }}" class="text-center border">
                        <td class="font-medium">{{ $inv_request->name }}</td>
                        <td class="font-medium">{{ $inv_request->USERNAME }}</td>
                        <td class="flex justify-evenly ">
                            <div class="text-4xl">
                                <form method="POST">
                                    @csrf
                                    <button type="submit" data-id="{{ $inv_request->id }}" data-invited_id="{{ $inv_request->invited_id }}" data-sender_id="{{ $inv_request->sender_id }}" data-group_id="{{ $inv_request->group_id }}" class="acceptInvRequest"><i class="fa-solid fa-circle-check" style="color: green;"></i></button>
                                </form>
                            </div>
                            <div class="text-4xl">
                                <form method="POST">
                                    @csrf
                                    <button type="submit" data-id="{{ $inv_request->id }}" class="declineInvRequest"><i class="fa-solid fa-circle-xmark" style="color: red;"></i></button>
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
