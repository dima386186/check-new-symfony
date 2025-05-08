$(document).ready(function () {
    $('#city').on('change', function () {
        var city = $(this).val();

        $('.detail').hide();

        if (city) {
            var request = {
                url: '/current-weather',
                data: {
                    q: city
                },
                headers: {
                    'Accept': 'application/json'
                }
            };

            $.get(request, function (jqXHR) {
                for (i in jqXHR.data) {
                    $('.location, .detail.' + i).show();
                    $('span.' + i).text(jqXHR.data[i]);
                }
            }).fail(function (jqXHR) {
                $('.error').show();
                $('.error-content').text(jqXHR.responseJSON.error);
            })

        }
    });
});
