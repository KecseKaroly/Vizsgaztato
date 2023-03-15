@extends('layouts.app')
@section('content')
    <div>
        <div>
            <div>Chats</div>
            <div id="chat">
                @foreach($group->groupMessages as $message)
                        <div> {{$message->user->name}} mondta: {{ $message->message }} </div>
                @endforeach
            </div>
            <div>
                <form method="POST">
                    @csrf
                    <input type="hidden" name="group_id" value="1"/>
                    <input type="hidden" name="name" value="{{ auth()->user()->name }}"/>
                    <input type="text" name="message" />
                    <button type="submit" id="sendGroupMessage">küld</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="module">
        var groupId = 1;
        Echo.private(`groupMessages.${groupId}`)
            .listen('GroupMessageSent', (e) => {
               var group_id = $("input[name=group_id]").val();
               $.ajax({
                   type: 'GET',
                   url: `/groups/${group_id}/message`,
                   success: function (data) {
                       $("#chat").append(`<div>${data.user.name} said: ${data.message}</div>`);
                   },
                   error: function (data) {
                       alert("Valami hiba történt...");
                   }
               });
            });
    </script>
@endpush
