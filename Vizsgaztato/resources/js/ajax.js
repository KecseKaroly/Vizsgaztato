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
          alert(data.success);
       }
    });

});
