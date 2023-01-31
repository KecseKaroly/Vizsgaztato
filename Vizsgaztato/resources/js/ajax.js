$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(".btn-submit").click(function(e){

    e.preventDefault();
    var invCode = $("input[name=invCode]").val();
    $.ajax({
       type:'POST',
       url:"/submit_join_request",
       data:{invCode:invCode},
       success:function(data){
          alert(data.msg);
       },
       error:function(data) {
        alert("Valami hiba történt...");
       }
    });

});

$(".declineJoinRequest").click(function(e){
    e.preventDefault();
    const params = $(this).data();
    const reqId = params["id"];
    $.ajax({
       type:'DELETE',
       url:"/decline_join_request",
       data:{join_request_id:reqId},
       success:function(data){
          alert(data.success);
          document.getElementById(`request-${reqId}`).remove();
       }
    });

});

$(".acceptJoinRequest").click(function(e){
    e.preventDefault();
    const params = $(this).data();
    const reqId = params["id"];
    const requester_id = params["requester_id"];
    const group_id = params["group_id"];
    $.ajax({
       type:'POST',
       url:"/accept_join_request",
       data:{join_request_id:reqId,requester_id:requester_id,group_id:group_id},
       success:function(data){
          alert(data.success);
          document.getElementById(`request-${reqId}`).remove();
       }
    });

});
