const cursorSmall = document.querySelector('.small');


const positionElement = (e)=> {
  const mouseY = e.pageY;
  const mouseX = e.pageX;

  cursorSmall.style.transform = `translate3d(${mouseX}px, ${mouseY}px, 0)`;

}

function linkedin() {
      window.location.href = "https://www.linkedin.com/in/andrea-blignaut/";
}

function github() {
      window.location.href = "https://github.com/andreabeatrice";
}

window.addEventListener('mousemove', positionElement)

$("#writing-to-work").click(function() {
    $([document.documentElement, document.body]).animate({
        scrollTop: $("#work").offset().top
    }, 1000);
});

$("#writing-to-about").click(function() {
    $([document.documentElement, document.body]).animate({
        scrollTop: $("#about").offset().top
    }, 1000);
});

$("#work-to-writing").click(function() {
    $([document.documentElement, document.body]).animate({
        scrollTop: $("#writing").offset().top
    }, 1000);
});

$("#work-to-about").click(function() {
    $([document.documentElement, document.body]).animate({
        scrollTop: $("#about").offset().top
    }, 1000);
});

$("#about-to-writing").click(function() {
    $([document.documentElement, document.body]).animate({
        scrollTop: $("#writing").offset().top
    }, 1000);
});

$("#about-to-work").click(function() {
    $([document.documentElement, document.body]).animate({
        scrollTop: $("#work").offset().top
    }, 1000);
});

let blurred_robot = false;
$(".card-robot").hover(function() {

	
	if (!blurred_robot) {
		$(".small").css("opacity", "0");
		blurred_robot = true;
		$(".card-robot").css("animation", "blurcard 0.5s forwards");
		$(".card-robot").css("animation-play-state", "running");
	}
	else {
		$(".small").css("opacity", "1");
		blurred_robot = false;
		$(".card-robot").css("animation", "unblur 0.5s forwards");
		$(".card-robot").css("animation-play-state", "running");
	}
	
});

let blurred_quizzical = false;
$(".card-quizzical").hover(function() {
	if (!blurred_quizzical) {
		$(".small").css("opacity", "0");
		blurred_quizzical = true;
		$(".card-quizzical").css("animation", "blurcard  0.5s forwards");
		$(".card-quizzical").css("animation-play-state", "running");
	}
	else {
		$(".small").css("opacity", "1");
		blurred_quizzical = false;
		$(".card-quizzical").css("animation", "unblur  0.5s forwards");
		$(".card-quizzical").css("animation-play-state", "running");
	}
	
});

let blurred_her = false;
$(".card-her").hover(function() {
	if (!blurred_her) {
		$(".small").css("opacity", "0");
		blurred_her = true;
		$(".card-her").css("animation", "blurcard 0.5s forwards");
		$(".card-her").css("animation-play-state", "running");
	}
	else {
		$(".small").css("opacity", "1");
		blurred_her = false;
		$(".card-her").css("animation", "unblur  0.5s forwards");
		$(".card-her").css("animation-play-state", "running");
	}
	
});

let blurred_bustle = false;
$(".card-bustle").hover(function() {
	if (!blurred_bustle) {
		$(".small").css("opacity", "0");
		blurred_bustle = true;
		$(".card-bustle").css("animation", "blurcard 0.5s forwards");
		$(".card-bustle").css("animation-play-state", "running");
	}
	else {
		$(".small").css("opacity", "1");
		blurred_bustle = false;
		$(".card-bustle").css("animation", "unblur 0.5s forwards");
		$(".card-bustle").css("animation-play-state", "running");
	}
	
});

/*
let blurred_her = false;
$(".card-her").hover(function() {
	if (!blurred_her) {
		blurred_her = true;
		$(".card-her").css("animation", "comeup 1s forwards");
		$(".card-her").css("animation-play-state", "running");
	}
	
	
});

let blurred_quizzical = false;
$(".card-quizzical").hover(function() {
	if (!blurred_quizzical) {
		blurred_quizzical = true;
		$(".card-quizzical").css("animation", "blurcard 1s forwards");
		$(".card-quizzical").css("animation-play-state", "running");
	}
	else {
		blurred_quizzical = false;
		$(".card-quizzical").css("animation", "unblurcard 1s forwards");
		$(".card-quizzical").css("animation-play-state", "running");
	}
	
});

$(window).scroll(function() {
  console.log($(window).scrollTop());
  console.log($(".card-quizzical").offset().top);
	
	var x = $(".card-quizzical").offset().top - $(window).scrollTop();
	
	if (x >= -100 && x <= 200) {
		console.log("AT QUIZZICAL");
	}

});
*/