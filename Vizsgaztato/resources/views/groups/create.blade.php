@extends('layouts.app')
@section('content')
<div class="mt-24 mb-24 select-none">
    <div class="flex flex-col max-w-full mx-auto rounded-xl overflow-hidden  lg:w-4/6 md:w-8/12 sm:w-11/12 w-11/12">
        <div class="flex">
            <div class="text-center mb-12 font-black text-3xl w-10/12">Csoport létrehozása</div>
        </div>
        <div class="bg-slate-50 w-11/12 rounded-xl">
            <form method="POST" action="{{ route('groups.store') }}">
                @csrf
                <div class="md:flex md:items-center mb-6 mt-10">
                    <div  class="md:w-2/5">
                        <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-8 md:mx-0 mx-3" for="group_name">
                            Csoport neve
                        </label>
                    </div>
                    <div class="md:w-3/5">
                      <input class="bg-gray-200 appearance-none border-2 border-gray-200 rounded md:w-8/12 w-11/12 py-3 md:mx-0 mx-3 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500" id="group_name" type="text" name="group_name" required autofocus>
                    </div>
                </div>
                <div class="md:flex md:items-center mb-6">
                    <div  class="md:w-2/5">
                        <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-8 md:mx-0 mx-3" for="group_invCode">
                            Meghívó kód
                        </label>
                    </div>
                    <div class="md:w-3/5">
                      <input class="bg-gray-200 appearance-none border-2 border-gray-200 rounded md:w-8/12 w-11/12 py-3 md:mx-0 mx-3 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500" id="group_invCode" type="text" name="group_invCode" value="{{ $invCode }}" required readonly>
                    </div>
                </div><div class="md:flex md:items-center mb-10 md:mx-0 mx-3">
                    <div class="md:w-2/5"></div>
                    <div class="md:w-3/5">
                      <button class="shadow bg-purple-500 hover:bg-purple-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded" type="submit">
                          {{ __('Létrehozás') }}
                      </button>
                    </div>
                  </div>
            </form>
        </div>
    </div>
</div>
@endsection
