$("#menu-careers").click(function() {
    $([document.documentElement, document.body]).animate({
        scrollTop: $("#careers").offset().top
    }, 1000);
});

$("#menu-contact").click(function() {
    $([document.documentElement, document.body]).animate({
        scrollTop: $("#news").offset().top
    }, 1000);
});

$("#menu-locator").click(function() {
    $([document.documentElement, document.body]).animate({
        scrollTop: $("#map").offset().top
    }, 1000);
});

$("#menu-top").click(function() {
    $([document.documentElement, document.body]).animate({
        scrollTop: 0
    }, 1000);
});