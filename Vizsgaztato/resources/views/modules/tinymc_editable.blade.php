<script src="{{ asset('tinymce/js/tinymce/tinymce.min.js') }}"></script>
<script>
    tinymce.init({
        paste_data_images: false,
        selector: 'textarea',
        language: 'hu_HU',
        plugins: 'table lists',
        toolbar: 'undo redo | formatselect| bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table',
        menubar: true,
        resize: false,});
</script>


