$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$(".removeUserFromGroup").click(function (e) {
    e.preventDefault();
    Swal.fire({
        title: 'Biztosan törli a felhasználót a csoportból?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Igen',
        cancelButtonText: 'Mégsem',
    }).then((result) => {
        if (result.isConfirmed) {
            const params = $(this).data();
            const reqId = params["id"];
            console.log(reqId);
            $.ajax({
                type: 'DELETE',
                url: `/group/user/${reqId}/remove`,
                data: {groups_users_id: reqId},
                success: function (data) {
                    if (data.success) {
                        document.getElementById(`group_user-${reqId}`).remove();
                        Swal.fire({
                            icon: 'success',
                            title: data.success,
                            text: data.message,
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Hiba!',
                            text: data.error,
                        })
                    }
                }
            });
        } else if (result.dismiss === "cancel") {
            Swal.fire('Művelet megszakítva', '', 'info')
        }
    })
});

$(".leaveFromGroup").click(function (e) {
    e.preventDefault();
    var form =  $(this).closest("form");
    Swal.fire({
        title: 'Biztosan kilép a csoportból?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Igen',
        cancelButtonText: 'Mégsem',
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        } else if (result.dismiss == "cancel") {
            Swal.fire('Művelet megszakítva', '', 'info');
        }
    });
});
$(".deleteTestAttempt").click(function (e) {
    e.preventDefault();
    Swal.fire({
        title: 'Biztosan törli a próbálkozást?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Igen',
        cancelButtonText: 'Mégsem',
    }).then((result) => {
        if (result.isConfirmed) {
            const params = $(this).data();
            const reqId = params["id"];
            $.ajax({
                type: 'DELETE',
                url: "/attempt/delete",
                data: {testAttemptId: reqId},
                success: function (data) {
                    if (data.success !== undefined) {
                        document.getElementById(`testAttempt#${reqId}`).remove();
                        Swal.fire({
                            icon: 'success',
                            title: 'Siker!',
                            text: data.error,
                        })
                    } else if (data.error !== undefined) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Hiba!',
                            text: data.error,
                        })
                    }
                }
            });
        } else if (result.dismiss == "cancel") {
            Swal.fire('Művelet megszakítva', '', 'info')
        }
    })
});
$(".deleteTestBtn").click(function (e) {
    e.preventDefault();
    var form =  $(this).closest("form");
    Swal.fire({
        title: 'Biztosan törli a tesztet?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Igen',
        cancelButtonText: 'Mégsem',
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        } else if (result.dismiss == "cancel") {
            Swal.fire('Művelet megszakítva', '', 'info');
        }
    });
});

$(".deleteGroupBtn").click(function (e) {
    e.preventDefault();
    var form =  $(this).closest("form");
    Swal.fire({
        title: 'Biztosan törli a csoportot?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Igen',
        cancelButtonText: 'Mégsem',
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        } else if (result.dismiss == "cancel") {
            Swal.fire('Művelet megszakítva', '', 'info');
        }
    });
});

$(".updateBtn").click(function (e) {
    e.preventDefault();
    var form =  $(this).closest("form");
    Swal.fire({
        title: 'Biztosan menti a változtatásokat?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Igen',
        cancelButtonText: 'Mégsem',
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        } else if (result.dismiss == "cancel") {
            Swal.fire('Művelet megszakítva', '', 'info');
        }
    });
});

$(".deleteCourseBtn").click(function (e) {
    e.preventDefault();
    var form =  $(this).closest("form");
    Swal.fire({
        title: 'Biztosan törli a kurzust?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Igen',
        cancelButtonText: 'Mégsem',
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        } else if (result.dismiss == "cancel") {
            Swal.fire('Művelet megszakítva', '', 'info');
        }
    });
});


$(".removeUserFromCourse").click(function (e) {
    e.preventDefault();
    Swal.fire({
        title: 'Biztosan törli a felhasználót a csoportból?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Igen',
        cancelButtonText: 'Mégsem',
    }).then((result) => {
        if (result.isConfirmed) {
            const params = $(this).data();
            const reqId = params["id"];
            $.ajax({
                type: 'DELETE',
                url: `/course/user/${reqId}/remove`,
                data: {courses_users_id: reqId},
                success: function (data) {
                    if (data.success) {
                        document.getElementById(`course_user-${reqId}`).remove();
                        Swal.fire({
                            icon: 'success',
                            title: data.success,
                            text: data.message,
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Hiba!',
                            text: data.error,
                        })
                    }
                }
            });
        } else if (result.dismiss === "cancel") {
            Swal.fire('Művelet megszakítva', '', 'info')
        }
    })
});

$(".leaveFromCourse").click(function (e) {
    e.preventDefault();
    var form =  $(this).closest("form");
    Swal.fire({
        title: 'Biztosan kilép a kurzusból?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Igen',
        cancelButtonText: 'Mégsem',
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        } else if (result.dismiss == "cancel") {
            Swal.fire('Művelet megszakítva', '', 'info');
        }
    });
});
