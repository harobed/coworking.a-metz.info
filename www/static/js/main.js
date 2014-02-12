$(document).ready(function() {
    var sequence = $("#sequence").sequence({
        autoPlay: true,
        nextButton: true,
        prevButton: true,
        preloader: true,
        navigationSkip: false
    }).data("sequence");

    sequence.afterLoaded = function(){
        $(".sequence-prev, .sequence-next").fadeIn(500);
    };
});
