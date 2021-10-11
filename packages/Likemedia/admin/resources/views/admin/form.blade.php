<script>
    jQuery(function() {
        var formData = '{{ $json }}',
            formRenderOpts = {
                dataType: 'json',
                formData: formData
            };

        var renderedForm = $('<div>');
        renderedForm.formRender(formRenderOpts);
    });
</script>
