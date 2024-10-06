function showDetails(element) {
    var itemId = element.getAttribute('data-id');
    $.ajax({
        url: 'get_inventory_details.php',
        type: 'GET',
        data: {
            id: itemId
        },
        success: function (response) {
            $('#item-details').html(response).addClass('active');
        }
    });
}

$(document).ready(function () {
    $('.search-bar').on('keyup', function () {
        var searchTerm = $(this).val().trim();
        if (searchTerm.length > 1) {
            $.ajax({
                url: 'search_client.php',
                type: 'GET',
                data: {
                    search: searchTerm
                },
                success: function (data) {
                    $('.inventory-container').html(data);
                }
            });
        } else {

            $.ajax({
                url: 'search_client.php',
                type: 'GET',
                success: function (data) {
                    $('.inventory-container').html(data);
                }
            });
        }
    });
});