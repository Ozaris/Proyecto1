const nav = document.querySelector("#nav");
const abrir = document.querySelector("#abrir");
const cerrar = document.querySelector("#cerrar");
const body = document.querySelector("Body");

abrir.addEventListener("click", () => {
    nav.classList.add("visible");
    body.classList.add("no-scroll");
});

cerrar.addEventListener("click", () => {
    nav.classList.remove("visible");
    body.classList.remove("no-scroll");
});

var swiper = new Swiper('.swiper-container', {
	
    navigation: {
	  nextEl: '.swiper-button-next',
	  prevEl: '.swiper-button-prev'
	},

	slidesPerView: 1,
	spaceBetween: 10,

	pagination: {
	  el: '.swiper-pagination',
	  clickable: true,
	},

	breakpoints: {
	  620: {
		slidesPerView: 1,
		spaceBetween: 20,
	  },
	  680: {
		slidesPerView: 2,
		spaceBetween: 40,
	  },
	  920: {
		slidesPerView: 3,
		spaceBetween: 40,
	  },
	} 
});
