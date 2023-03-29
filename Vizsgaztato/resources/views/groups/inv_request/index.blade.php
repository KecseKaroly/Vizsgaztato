@extends('layouts.app')
@section('title', 'Meghívók')
@section('content')
<div class="mt-4 mb-24">
    <div class="md:w-1/12 md:ml-12 mb-4 mr-8">
        <a href="{{route('groups.index')}}">
            <button
                class="text-center my-2 ml-4  py-1.5 text-lg font-bold text-blue-900 bg-slate-100 rounded-md w-full">
                Vissza
            </button>
        </a>
    </div>
    <div class="flex flex-col max-w-full mx-auto rounded-xl overflow-hidden w-11/12">
        <div class="flex">
            <div class="text-center mb-12 font-black text-3xl">Meghívások csoportokba</div>
        </div>
        <div class="bg-slate-50 rounded-xl divide-y pb-3">
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
                    {{ $inv_requests->links() }}
                </tbody>
              </table>
        </div>
    </div>
</div>
@endsection
