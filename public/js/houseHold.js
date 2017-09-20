$(function() {
    $('.note').tooltip({
        items: "img",
        content: function() {
            var element = $(this);
            return element.attr("alt");
        },
        track: true
    });
});