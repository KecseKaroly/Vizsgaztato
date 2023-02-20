$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

/* Group join requests: */
$("#sendInvCode").click(function (e) {
    e.preventDefault();
    var invCode = $("input[name=invCode]").val();
    $.ajax({
        type: 'POST',
        url: "/join_request/submit",
        data: {invCode: invCode},
        success: function (data) {
            if(data.success !== undefined) {
                $("#successfulJoinRequest").css("display", "flex");
                $("#successMessage").text(data.success);
            }
            if(data.failed !== undefined) {
                $("#failedJoinRequest").css("display", "flex");
                $("#failMessage").text(data.failed);
            }
        },
        error: function (data) {
            console.log(data);
            alert("Valami hiba történt...");
        }
    });
});
$(".declineJoinRequest").click(function (e) {
    e.preventDefault();
    const params = $(this).data();
    const reqId = params["id"];
    $.ajax({
        type: 'DELETE',
        url: "/join_request/decline",
        data: {join_request_id: reqId},
        success: function (data) {
            alert(data.success);
            document.getElementById(`request-${reqId}`).remove();
        }
    });
});
$(".acceptJoinRequest").click(function (e) {
    e.preventDefault();
    const params = $(this).data();
    const reqId = params["id"];
    const requester_id = params["requester_id"];
    const group_id = params["group_id"];
    $.ajax({
        type: 'POST',
        url: "/join_request/accept",
        data: {join_request_id: reqId, requester_id: requester_id, group_id: group_id},
        success: function (data) {
            alert(data.success);
            document.getElementById(`request-${reqId}`).remove();
        }
    });
});


/* Group invite requests*/
$(".declineInvRequest").click(function (e) {
    e.preventDefault();
    const params = $(this).data();
    const reqId = params["id"];
    $.ajax({
        type: 'DELETE',
        url: "/inv_request/decline",
        data: {inv_request_id: reqId},
        success: function (data) {
            alert(data.success);
            document.getElementById(`request-${reqId}`).remove();
        }
    });

});

$(".acceptInvRequest").click(function (e) {
    e.preventDefault();
    const params = $(this).data();
    const reqId = params["id"];
    const invited_id = params["invited_id"];
    const sender_id = params["sender_id"];
    const group_id = params["group_id"];
    $.ajax({
        type: 'POST',
        url: "/inv_request/accept",
        data: {inv_request_id: reqId, sender_id: sender_id, group_id: group_id, invited_id: invited_id},
        success: function (data) {
            alert(data.success);
            document.getElementById(`request-${reqId}`).remove();
        }
    });
});

/* TestInfo-n a csoportok és felhasználók kinyitása/összecsukása */
$(".group_id").click(function (e) {
    const params = $(this).data();
    const group_id = params["group_id"];
    $(`#attempts_of_group${group_id}`).toggle('fast');
    $(`#arrow_of_group${group_id}`).toggleClass('fa-solid fa-angles-down fa-solid fa-angles-up');
});
$(".user_id").click(function (e) {
    const params = $(this).data();
    const user_id = params["user_id"];
    const group_id = params["group_id"];
    $(`#attempts_of_user${user_id}_${group_id}`).toggle('fast');
    $(`#arrow_of_user${user_id}_${group_id}`).toggleClass('fa-solid fa-angles-down fa-solid fa-angles-up', 1000);
});



$(".deleteTestGroups").click(function (e) {
    e.preventDefault();
    var test_group_id = $(this).data()["test_group_id"];
    $.ajax({
        type: 'DELETE',
        url: "/test/groups/delete",
        data: {test_group_id: test_group_id},
        success: function (data) {
            if(data.success !== undefined)
                document.getElementById(`test_group${test_group_id}`).style.display = "none";
        },
        error: function (data) {
            alert("Valami hiba történt...");
        }
    });
});
$(".copyInvCode").click(function (e) {
    const params = $(this).data();
    const invCode = params["invcode"];
    navigator.clipboard.writeText(invCode);
    $("#invCodeCopiedMessage").show();
    $("#copyMessage").html(`Vágólapra másolva`);
    setTimeout(function () {
        $("#invCodeCopiedMessage").fadeOut(400);
    }, 3000);
})
$(".removeUserFromGroup").click(function (e) {
    e.preventDefault();
    const params = $(this).data();
    const reqId = params["id"];
    console.log(reqId);
    $.ajax({
        type: 'DELETE',
        url: "/removeUserFromGroup",
        data: {groups_users_id: reqId},
        success: function (data) {
            if(data.success !== undefined)
                document.getElementById(`group_user-${reqId}`).remove();
        }
    });

});

$(".deleteTestAttempt").click(function (e) {
    if (confirm("Biztosan törölni szeretné?")) {
        e.preventDefault();
        const params = $(this).data();
        const reqId = params["id"];
        console.log(reqId);
        $.ajax({
            type: 'DELETE',
            url: "/deleteTestAttempt",
            data: {testAttemptId: reqId},
            success: function (data) {
                if(data.success !== undefined);
                    document.getElementById(`testAttempt#${reqId}`).remove();
            }
        });
    }
});

$(".showGroupTestInfo").click(function (e) {
    const params = $(this).data();
    $("#enabled_from").val(params["enabled_from"].slice(0, -3));
    $("#enabled_until").val(params["enabled_until"].slice(0, -3));
    $("#test_id").val(params["test_id"]);
    $("#group_id").val(params["group_id"]);
});


