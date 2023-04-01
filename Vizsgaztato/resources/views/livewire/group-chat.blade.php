<div class="mt-4 mx-auto" xmlns="http://www.w3.org/1999/html">
    <div class="md:w-1/12 md:ml-12 mb-4 mr-8">
        <a href="{{route('groups.show', $group)}}">
            <button
                class="text-center my-2 ml-4  py-1.5 text-lg font-bold text-blue-900 bg-slate-100 rounded-md w-full">
                Vissza
            </button>
        </a>
    </div>
    <div class="flex flex-col max-w-full mx-auto rounded-xl overflow-hidden w-11/12">
        <div class="bg-slate-50 w-2/3 rounded-xl  mx-auto divide-y-4">
            <div class=" pb-6">
                <div id="messages" class="mx-8 overflow-auto h-96">
                    @foreach($group->groupMessages as $messageIndex => $message)
                        <div class="group">
                            {{-- Ha az előző üzenet is a mostani üzenet küldőjétől jött--}}
                            @if(!$loop->first && $message->user_id == $group->groupMessages[$messageIndex - 1]->user_id)
                                <div @class([
                                    'px-4',
                                    $message->user_id == auth()->id() ? 'text-right bg-blue-400 lg:ml-56 md:ml-32 sm:ml-24 ml-6' : 'text-left bg-blue-200 lg:mr-56 md:mr-32 sm:mr-24 mr-6',
                                    'pb-4 rounded-b-3xl' => $messageIndex == $loop->count-1 || $message->user_id != $group->groupMessages[$messageIndex + 1]->user_id,
                                ]) id="msg#{{$message->id}}">
                                    <div> {{ $message->message }}</div>
                                </div>
                            @else
                                @if($message->user_id == auth()->id())
                                    <div @class([
                                        "mt-3 text-right pt-4 bg-blue-400 px-4 rounded-t-3xl lg:ml-56 md:ml-32 sm:ml-24 ml-6",
                                        'pb-4 rounded-b-3xl' => $messageIndex == $loop->count-1 || $message->user_id != $group->groupMessages[$messageIndex + 1]->user_id,
                                        ]) id="msg#{{$message->id}}">
                                        <div class="flex justify-end">
                                            <div
                                                class="text-xs hidden group-hover:block group-hover:pr-4 italic"> {{ $message->created_at }}</div>
                                            <div
                                                class="text-xs font-semibold underline"> {{ $message->user->name }} </div>
                                        </div>
                                        <div> {{ $message->message }}</div>
                                    </div>
                                @else
                                    <div @class([
                                        "mt-3 text-left pt-4 bg-blue-200  px-4 rounded-t-3xl lg:mr-56 md:mr-32 sm:mr-24 mr-6",
                                        'pb-4 rounded-b-3xl' => $messageIndex == $loop->count-1 || $message->user_id != $group->groupMessages[$messageIndex + 1]->user_id,
                                        ]) id="msg#{{$message->id}}">
                                        <div class="flex justify-start">
                                            <div
                                                class="text-xs pr-4 font-semibold underline"> {{ $message->user->name }} </div>
                                            <div
                                                class="text-xs hidden group-hover:block italic"> {{ $message->created_at }}</div>
                                        </div>
                                        <div> {{ $message->message }}</div>
                                    </div>
                                @endif
                            @endif

                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mx-8 py-6 flex justify-around">
                <form method="POST" class="w-full" id="groupMessageForm">
                    @csrf
                    <input type="hidden" name="group_id" value="1"/>
                    <input type="hidden" name="name" value="{{ auth()->user()->name }}"/>
                    <div
                        class="md:mt-0 my-2 font-thin italic font-mono flex justify-between relative border rounded-md bg-slate-200 text-center">
                        <textarea rows="1" name="message" id="messageTextArea"
                                  class="border-none bg-transparent text-stone-600 w-11/12 focus:ring-2 focus:bg-slate-100 focus:ring-blue-500 focus:rounded-md"></textarea>
                        <button type="submit" id="sendGroupMessage"
                                class="w-10 mx-auto text-blue-700 px-2 border-slate-100 rounded bg-white rounded-full">
                            <i class="fa-solid fa-paper-plane"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@push('scripts')
    <script>
        var lastMessage = @json( $group->groupMessages->last() );
        $(document).ready(function () {
            var messageDiv = document.getElementById("messages");
            messageDiv.scrollTop = messageDiv.scrollHeight;
            $("#messageTextArea").keypress(function (e) {
                if(e.keyCode === 13 && !e.shiftKey){
                    e.preventDefault();
                    sendForm();
                    return true;
                }
            });
            $("#groupMessageForm").submit(function (e) {
                e.preventDefault();
                sendForm();
            });
        });

        function sendForm() {
            var message = $("#messageTextArea").val();
            var name = $("input[name=name]").val();
            var group_id = $("input[name=group_id]").val();
            $.ajax({
                type: 'POST',
                url: `/groups/${group_id}/message`,
                data: {message: message},
                success: function (data) {
                    $("#messageTextArea").val('');
                    Livewire.emit('newMessageReceived', data);
                },
                error: function (data) {
                    alert("Valami hiba történt...");
                }
            });
        }

        window.addEventListener('messageAdded', (e) => {
            document.getElementById('messages').scroll({ top: document.getElementById('messages').scrollHeight, behavior: "smooth"})
        });
    </script>
    @vite('resources/js/bootstrap.js')
    <script type="module">
        var groupId = 1;
        Echo.private(`groupMessages.${groupId}`)
            .listen('GroupMessageSent', (e) => {
                var group_id = $("input[name=group_id]").val();
                $.ajax({
                    type: 'GET',
                    url: `/groups/${group_id}/message`,
                    success: function (data) {
                        Livewire.emit('newMessageReceived', data);
                    },
                    error: function (data) {
                        alert("Valami hiba történt...");
                    }
                });
            });
    </script>
@endpush


