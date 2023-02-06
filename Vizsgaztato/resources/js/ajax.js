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

$(".declineInvRequest").click(function(e){
    e.preventDefault();
    const params = $(this).data();
    const reqId = params["id"];
    $.ajax({
       type:'DELETE',
       url:"/decline_inv_request",
       data:{inv_request_id:reqId},
       success:function(data){
          alert(data.success);
          document.getElementById(`request-${reqId}`).remove();
       }
    });

});

$(".acceptInvRequest").click(function(e){
    e.preventDefault();
    const params = $(this).data();
    const reqId = params["id"];
    const invited_id = params["invited_id"];
    const sender_id = params["sender_id"];
    const group_id = params["group_id"];
    $.ajax({
       type:'POST',
       url:"/accept_inv_request",
       data:{inv_request_id:reqId,sender_id:sender_id,group_id:group_id,invited_id:invited_id},
       success:function(data){
          alert(data.success);
          document.getElementById(`request-${reqId}`).remove();
       }
    });
});

$(".user_id").click(function(e){
    const params = $(this).data();
    const user_id = params["user_id"];
    const group_id = params["group_id"];
    let element = document.getElementById(`attempts_of_user${user_id}_${group_id}`);
    if(element.style.display === "none")
        element.style.display = "block";
    else element.style.display = "none";
});

$(".group_id").click(function(e){
    const params = $(this).data();
    const group_id = params["group_id"];
    let element = document.getElementById(`attempts_of_group${group_id}`);
    if(element.style.display === "none")
        element.style.display = "block";
    else element.style.display = "none";
});

$(".updateTestGroups").click(function(e){
    e.preventDefault();
    var test_group_id = $(this).data()["test_group_id"];
    var enabled_from = $("input[name=enabled_from]").val();
    var enabled_until = $("input[name=enabled_until]").val();
    $.ajax({
       type:'POST',
       url:"/test/groups",
       data:{test_group_id:test_group_id, enabled_from:enabled_from, enabled_until:enabled_until},
       success:function(data){
          alert("Sikeres módosítás");
       },
       error:function(data) {
        alert("Valami hiba történt...");
       }
    });
});

$(".deleteTestGroups").click(function(e){
    e.preventDefault();
    var test_group_id = $(this).data()["test_group_id"];
    $.ajax({
       type:'DELETE',
       url:"/test/groups/delete",
       data:{test_group_id:test_group_id},
       success:function(data){
          document.getElementById(`test_group${test_group_id}`).style.display="none";
       },
       error:function(data) {
        alert("Valami hiba történt...");
       }
    });
});
