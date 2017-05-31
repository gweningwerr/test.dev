$(document).ready(function () {

    $(document).on('click', '.status-btn', function () {
        var url = $(this).attr('data-url');

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function (json) {
                if (json.res == 1) {
                    $('.status-row').html(json.html);
                } else {
                    alert(json.msg);
                }
            }
        });
    });

});