(function ($) {
    $(document).ready(function () {

        $('.HTTPS_Direct_HTTPS_Direct .status').on('change', ':checkbox', (e) => {
            let current = $(e.currentTarget);
            enable_disable(current.is(':checked') ? 'https_enable' : 'https_disable');
        })

    });

    function enable_disable(action) {
        $.ajax({
            type: 'POST',
            url: WPURLS.admin_url + "/wp-admin/admin-ajax.php",
            data: {
                action: action
            },
            success: function (response) {
                location.reload();
            }

        });
    }
}(jQuery));