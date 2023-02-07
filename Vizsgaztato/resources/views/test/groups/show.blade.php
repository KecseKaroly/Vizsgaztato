@extends('layouts.app')
@section('title', 'Teszt csoportjai')
@section('content')
<div class="mt-24 mb-24">
    <div class="flex flex-col max-w-full mx-auto rounded-xl overflow-hidden  lg:w-10/12 md:w-8/12 sm:w-11/12 w-11/12">
        <div class="flex">
            <div class="text-center mb-12 font-black text-3xl w-full">Teszthez rendelt csoportok</div>
        </div>
        <div class="bg-slate-50  rounded-xl">
            @foreach($test_groups as $test_group)
                <div id="test_group{{ $test_group->id }}" class="m-6 px-3 py-6 rounded text-lime-50 bg-slate-600  items-center">
                    <div class="text-2xl font-bold mb-4 ml-12 text-stone-50">{{ $test_group->name }}</div>
                    <div class="md:flex justify-around">
                        <div class="w-4/5">
                            <form>
                                @csrf
                                <div date-rangepicker  datepicker-format="yyyy/mm/dd" datepicker-autohide >
                                    <div class="sm:flex sm:justify-between mb-3">
                                        <div><label for="enabled_from">Teszt engedélyezésének kezdete: </label></div>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                            </div>
                                            <input name="enabled_from" id="enabled_from" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Select date start" datepicker-title="Teszt engedélyezésének kezdete" value="{{ $test_group->enabled_from }}">
                                        </div>
                                    </div>
                                    <div class="sm:flex sm:justify-between">
                                        <div><label for="enabled_until">Teszt engedélyezésének vége: </label></div>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                            </div>
                                            <input name="enabled_until" id="enabled_until" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Select date end" datepicker-title="Teszt engedélyezésének vége"  value="{{ $test_group->enabled_until }}">
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" data-test_group_id="{{ $test_group->id }}" class="updateTestGroups bg-green-600 border rounded-lg text-green-50 p-3 font-semibold text-lg mt-4 ml-3"><i class="fa-regular fa-floppy-disk"></i> Mentés</button>
                            </form>
                        </div>
                        <div class="mt-4 flex justify-center">
                            <div></div>
                            <div>
                                <form>
                                    @csrf
                                    @METHOD('DELETE')
                                    <button type="submit" data-test_group_id="{{ $test_group->id }}" class="deleteTestGroups bg-red-600 border rounded-lg text-red-50 p-3 font-semibold text-lg ml-3 md:ml-5"><i class="fa-solid fa-trash"></i> Törlés</button>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
