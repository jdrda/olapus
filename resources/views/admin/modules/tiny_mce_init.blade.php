<script type="text/javascript">
    tinymce.init({
        selector: ".html",
        theme: "silver",
        entity_encoding : "raw",
        plugins: [
            "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
            "insertdatetime media nonbreaking save table directionality",
            "template paste textpattern imagetools"
        ],
        toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
        toolbar2: "print preview media | forecolor backcolor",
        image_advtab: true,
        language: '{{ env('TINYMCE_LOCALIZATION') }}',
    });
</script>