@extends('layouts.app')
@section('title', 'Regisztráció')

@section('content')
    <div class="flex flex-col items-center justify-left border rounded-3xl bg-slate-50 mt-16 md:mx-24 mx-8 py-8">
        <div class="text-4xl font-bold mb-8 text-center">Regisztráció</div>
        <hr class="w-10/12 h-1 mx-auto bg-gray-100 border-0 rounded md:mb-10 dark:bg-gray-700">
        <div class="w-full flex flex-col items-center ">
            <form class="w-9/12" method="POST" action="{{ route('register') }}">
                @csrf
                <div class="md:flex md:items-center mb-2">
                    <div class="md:w-4/12">
                        <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-8" for="name">
                            Teljes név
                        </label>
                    </div>
                    <div class="md:w-4/12">
                        @error('name') <span class="text-sm text-red-500 font-bold">{{ $message }}</span> @enderror
                        <input
                            class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                            id="name" type="text" class="form-control @error('username') is-invalid @enderror"
                            name="name" value="{{ old('name') }}" autofocus>
                    </div>
                </div>
                <div class="md:flex md:items-center mb-2">
                    <div class="md:w-4/12">
                        <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-8" for="username">
                            Felhasználónév
                        </label>
                    </div>
                    <div class="md:w-4/12">
                        @error('username') <span class="text-sm text-red-500 font-bold">{{ $message }}</span> @enderror
                        <input
                            class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                            id="username" type="text" class="form-control @error('username') is-invalid @enderror"
                            name="username" value="{{ old('username') }}" autocomplete="username">
                    </div>
                </div>
                <div class="md:flex md:items-center mb-2">
                    <div class="md:w-4/12">
                        <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-8" for="email">
                            E-mail cím
                        </label>
                    </div>
                    <div class="md:w-4/12">
                        @error('email') <span class="text-sm text-red-500 font-bold">{{ $message }}</span> @enderror
                        <input
                            class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                            id="email" type="text" class="form-control @error('username') is-invalid @enderror"
                            name="email" value="{{ old('email') }}" autofocus autocomplete="email">
                    </div>
                </div>
                <div class="md:flex md:items-center mb-2">
                    <div class="md:w-4/12">
                        <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-8" for="password">
                            {{ __('Jelszó') }}
                        </label>
                    </div>
                    <div class="md:w-4/12">
                        @error('password') <span class="text-sm text-red-500 font-bold">{{ $message }}</span> @enderror
                        <input
                            class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                            id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                            name="password" autocomplete="new-password">
                    </div>
                </div>
                <div class="md:flex md:items-center mb-2">
                    <div class="md:w-4/12">
                        <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-8"
                               for="password-confirm">
                            {{ __('Jelszó megerősítése') }}
                        </label>
                    </div>
                    <div class="md:w-4/12">
                        <input
                            class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500 form-control"
                            id="password-confirm" type="password" name="password_confirmation"
                            autocomplete="new-password" >
                    </div>
                </div>
                <div class="md:flex md:items-center mb-2">
                    <div class="md:w-4/12">
                        <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-8" for="is_student">
                            {{ __('Diák?') }}
                        </label>
                    </div>
                    <div class="md:w-4/12 mr-2">
                        <input type="checkbox" checked class="focus:ring-purple-600 rounded-lg text-purple-500"
                               id="is_student" name="is_student">
                    </div>
                </div>
                <div class="md:flex md:items-center mb-4">
                    <div class="md:w-4/12">
                    </div>
                    <div class="md:w-4/12">
                        <div>@error('acceptTOS') <span class="text-sm text-red-500 font-bold">{{ $message }}</span> @enderror</div>

                        <input type="checkbox" unchecked class="focus:ring-purple-600 rounded-lg text-purple-500"
                               id="acceptTOS" name="acceptTOS">
                        <label class="text-gray-500 font-bold" for="acceptTOS">
                            Elfogadom a <button data-modal-target="ToSModal"
                                                data-modal-toggle="ToSModal"
                                                >felhasználási feltételeket</button>*
                        </label>
                    </div>
                </div>
                <div class="md:flex md:items-center">
                    <div class="md:w-4/12"></div>
                    <div class="md:w-4/12">
                        <button
                            class="shadow bg-purple-500 hover:bg-purple-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded"
                            type="submit">
                            {{ __('Regisztráció') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <div id="ToSModal" tabindex="-1"
         class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
        <div class="relative w-full h-full max-w-2xl md:h-auto">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <button data-modal-hide="ToSModal" type="button"
                        class="text-gray-400 bg-transparent hover:bg-red-500 hover:text-white rounded-lg p-1.5">
                    <i class="fa-solid fa-xmark fa-2xl"></i>
                    <span class="sr-only">Bezárás</span>
                </button>
                <div class="px-16 py-8">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Amet debitis deserunt harum magnam, provident repudiandae tenetur? Architecto assumenda autem enim fugit labore maxime nemo, neque, nihil odio perferendis quod repellendus?
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Animi, aperiam, commodi impedit incidunt ipsa natus nobis quod reiciendis repellendus sapiente sequi sit suscipit temporibus voluptas voluptatem. Magnam repellat voluptas voluptatem.
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab atque id ipsam sed. A ad adipisci dolorum eius exercitationem fuga id, in molestias mollitia provident quae quas saepe totam veritatis!
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ad alias architecto, atque consequatur cupiditate dignissimos dolor eaque eos facere id, ipsa iure maiores natus nisi obcaecati placeat, quis saepe? Repudiandae!
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci animi ea eos molestias quibusdam quis quo, totam vel voluptas voluptatem. Ad aliquid dignissimos molestias mollitia, nisi officia optio repellat sed!
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam consequatur, impedit necessitatibus possimus repellat sapiente tempora. Adipisci aliquam autem commodi deleniti, dolores ducimus fugit illum maxime, quam, quas quos similique.
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Autem corporis, cumque fugiat iste magni neque nihil pariatur quaerat, quis quos reprehenderit saepe voluptates voluptatum. Consequuntur deleniti neque nesciunt quaerat rerum!
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Culpa fuga fugit nam non nulla! Alias aspernatur beatae ea eos itaque iure minima modi neque nostrum ratione tempora, tempore unde ut!
                </div>
                <div class="flex items-center p-6 justify-evenly border-t-2">
                    <button data-modal-hide="ToSModal" type="button"
                            class="border rounded bg-transparent hover:bg-stone-300 text-black p-2 ">Bezár
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
