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

                <div class="md:flex md:items-center mb-4">
                    <div class="md:w-4/12">
                    </div>
                    <div class="md:w-4/12">
                        <div>@error('acceptTOS') <span class="text-sm text-red-500 font-bold">{{ $message }}</span> @enderror</div>

                        <input type="checkbox" unchecked class="focus:ring-purple-600 rounded-lg text-purple-500"
                               id="acceptTOS" name="acceptTOS">
                        <label class="text-gray-500 font-bold text-sm" for="acceptTOS">
                            Elfogadom az <button type="button" data-modal-target="ToSModal"
                                                data-modal-toggle="ToSModal"
                                                >adatvédelmi nyilatkozatot</button>*
                        </label>
                    </div>
                </div>
                <div class="md:flex md:items-center md:text-left mt-6">
                    <div class="md:w-4/12">
                    </div>
                    <div class="md:w-4/12">
                        Már regisztrált?
                        <a class="text-blue-500 font-bold mb-1 md:mb-0"
                           href="{{ route('login') }}">
                            {{ __('Jelentkezzen be itt!') }}
                        </a>
                    </div>
                </div>
                <div class="md:flex md:items-center">
                    <div class="md:w-4/12"></div>
                    <div class="md:w-4/12" >
                        <button
                            id="register"
                            class="shadow bg-purple-500 hover:bg-purple-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded"
                            type="submit">
                            {{ __('Regisztrálás') }}
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
                <div class="px-16 py-8 prose h-72 overflow-y-auto bg-red-100 mx-auto">
                    <ol>
                        <li>
                                Tudomásul veszem, hogy járulékos kötelezettségeként köteles vagyok az Eszterházy Károly Katolikus Egyetem (a továbbiakban: Egyetem) gazdasági érdekeinek védelmére, továbbá a munkám során tudomásomra jutott</li>
                            <ul>
                                <li>	üzleti titkot (a gazdasági tevékenységhez kapcsolódó minden olyan tényt, információt, megoldást vagy adatot, amelynek nyilvánosságra hozatala, illetéktelenek által történő megszerzése vagy felhasználása a jogosult jogszerű pénzügyi, gazdasági vagy piaci érdekeit sértené vagy veszélyeztetné), illetve</li>
                                <li>	a kutatási tevékenységhez kapcsolódó minden olyan témát, információt, megoldást, eljárási módszert, adatot, amelynek titokban maradásához az Egyetemnek méltányolható érdeke fűződik, valamint</li>
                                <li>	a munkavégzés során tudomásomra jutott személyes adatokat,</li>
                                <li>	az Egyetemre, illetve a tevékenységére vonatkozó alapvető fontosságú információkat megőrizni.</li>
                            </ul>
                        <li>	Kötelezettséget vállalok arra, hogy az 1. pontban rögzítetteken túlmenően sem közlök illetéktelen személlyel olyan adatot, amely a köztem és az Egyetem között fennálló jogviszony során jutott a tudomásomra, és amelynek közlése az Egyetem vagy más személy számára hátrányos következménnyel járna.</li>
                        <li>	Tudomásul veszem, hogy titoktartási kötelezettségem nem terjed ki a közérdekű adatok nyilvánosságára és a közérdekből nyilvános adatra vonatkozó, jogszabály vagy hatóság által előírt adatszolgáltatási és tájékoztatási kötelezettségre.</li>
                        <li>	Tudomásul veszem, hogy titoktartási kötelezettségem a munkaviszonyom megszűnését követően is fennáll, erről szóló külön megállapodás nélkül is.</li>
                        <li>	Tudomásul veszem, hogy amennyiben a titoktartási kötelezettségemet vétkesen, vagy nekem felróható okból megszegem, akkor az Egyetem a kötelezettségszegésből eredő károk megtérítését követelheti – illetve annak jogi úton érvényt szerez – az okozott kár értékének függvényében.</li>
                        <li>	Tudomásul veszem, hogy a kutatási eredmények tulajdonjoga, valamint hasznosíthatósága vonatkozásában a vonatkozó jogszabályokat, valamint az Egyetem Szellemi tulajdon kezelési szabályzatát a közöttünk fennálló munkaviszony időtartama alatt magamra nézve kötelezőnek ismerem el.</li>
                        <li>	Tudomásul veszem, hogy a korábban részletezett kutatási program során a titoktartás tárgykörébe tartozó kérdésekben a szakmai irányítóval köteles vagyok egyeztetni.</li>
                        <li>	Eltérő megállapodás hiányában kötelezettséget vállalok arra, hogy az Egyetemmel fennálló jogviszonyom megszűnését követő öt munkanapon belül a kutatással kapcsolatos információkat, eredményeket tartalmazó dokumentációkat az Egyetem részére visszaszolgáltatom.</li>
                        <li>	Tudomásul veszem, hogy a munkaköri feladataim ellátása során tudomásomra jutott személyes adatokat kizárólag az adatkezelő utasításai szerint kezelem, a lehető legteljesebb adatbiztonság érdekében meghozom a szükséges intézkedéseket, segítem az adatkezelőt a hatóság felé fennálló kötelezettségeinek a teljesítésében.</li>
                        <li>	Tájékoztatom a munkáltatói jogkör gyakorlóját, ha annak valamely utasítása adatvédelmi rendelkezéseket sért.</li>
                        <li>	Köteles vagyok arra, hogy a munkaviszonyom megszűnését követően az adatkezelő döntése alapján minden személyes adatot törlök vagy visszajuttatok az adatkezelőnek, és törlöm a meglévő másolatokat, kivéve, ha az uniós vagy a tagállami jog a személyes adatok tárolását írja elő.</li>
                        <li>	Tudomásul veszem, hogy az adatkezelő épületeiben a GDPR 6. cikk (1) bekezdés f) pontja alapján vagyonvédelmi és ellenőrzési célból elektronikus megfigyelőrendszert (kamerákat) üzemeltet.</li>
                    </ol>
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
