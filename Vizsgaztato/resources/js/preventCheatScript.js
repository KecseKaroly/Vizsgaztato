var left = 0;
var mouseleft = false;
var kicked = false;
var testDiv = $(".testDiv");
testDiv.mouseleave(function(){
    console.log("left");
    mouseleft = true;
    left = Date.now();
});
testDiv.mouseenter(function(){
    if(mouseleft && !kicked) {
        mouseleft = false;
        const diff = left - Date.now();
        if(diff < -1000)
        {
            kicked = true;
            Swal.fire({
                icon: 'warning',
                title: 'Teszt vége!',
                text: 'Túl sok ideig tartózkodott az engedélyezett tartományon kívül!',
                confirmButtonText: 'Értem',
                allowOutsideClick: false,
                allowEscapeKey: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    endTest();
                }
            });
        }
    }
});
