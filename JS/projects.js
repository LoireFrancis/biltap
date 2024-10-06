$(document).ready(function () {
    $('.search-bar-projects').on('keyup', function () {
        var searchTerm = $(this).val().trim();
        if (searchTerm.length > 1) {
            $.ajax({
                url: 'search_projects.php',
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
                url: 'search_projects.php',
                type: 'GET',
                success: function (data) {
                    $('.inventory-container').html(data);
                }
            });
        }
    });
});