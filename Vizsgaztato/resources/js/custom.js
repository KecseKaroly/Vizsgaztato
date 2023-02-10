
        //https://stackoverflow.com/questions/15369572/jquery-focusout-for-entire-div-and-children
    let element = document.getElementById("container");
    element.addEventListener('focusout', function(event) {
        if (element.contains(event.relatedTarget)) {
            return;
        }
        document.getElementById('searchResults').style.display='none';
    });
    element.addEventListener('focusin', function(event) {
        if (element.contains(event.relatedTarget)) {
            return;
        }
        document.getElementById('searchResults').style.display='block';
    });
