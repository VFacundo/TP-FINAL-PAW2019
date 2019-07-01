var document = document || {},
    console = console || {},
    window = window || {},
    Base = Base || {};

Base.init = function(){
	window.addEventListener("DOMContentLoaded", function(){
    var botonLi = document.getElementsByClassName("botonLi"),
			initSel = 0,
			menu = document.querySelector("#menuBtn"),
			location = window.location.href;
		if(location.search("perfil")!=-1){
			initSel = 0;
		}else if (location.search("equipo")!=-1) {
			initSel = 1;
		}else if (location.search("turnos")!=-1) {
			initSel = 2;
		}else if (location.search("buscar")!=-1) {
			initSel = 3;
		}else {
			initSel = 0;
		}
		botonLi[initSel].classList.add("selectedLi");
		for (var i = 0; i < botonLi.length; i++) {
			botonLi[i].addEventListener("click",Base.click);
		}
		menu.addEventListener("click",function(){showNav()});
	});
}

function showNav(){
	var nav = document.querySelector("body > nav"),
		menu = document.querySelector("body > nav nav"),
		menuBtn = document.querySelector("#menuBtn");
	if(menuBtn.innerText === '>'){
		nav.classList.add('mobileMenu');
		nav.classList.add('goRight');
		setTimeout(function(){
			menuBtn.innerText = '<';
			nav.classList.remove('goRight');
		}, 999);
	}else{
		nav.classList.add('goLeft');
		setTimeout(function(){
			nav.classList.remove('mobileMenu');
			nav.classList.remove('goLeft');
		}, 399);
		menuBtn.innerText = '>';
		
	}
	
}

Base.click = function(){
  var selected = event.target,
	  old_selected = document.getElementsByClassName("selectedLi");
    old_selected[0].classList.remove("selectedLi");
    selected.classList.add("selectedLi");
}