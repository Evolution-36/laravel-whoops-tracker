$(function() {
    $(".confirmation").click(function(event) {
        let message = $(this).data('message') ? $(this).data('message') : 'Are you sure you want to do this?';
        if (!confirm(message)) {
            event.preventDefault();
        }
    });
});
