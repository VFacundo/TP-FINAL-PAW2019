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
		nav.style.position = 'absolute';
		nav.style.gridArea = 'b';
		nav.style.left = '-100wv';
		nav.classList.add('goRight');
		nav.style.width = '100vw';
		menu.style.width = '65vw';
		nav.style.backgroundColor = '#fffffffa';
		setTimeout(function(){
			menuBtn.innerText = '<';
			nav.classList.remove('goRight');
		}, 499);
	}else{
		nav.classList.add('goLeft');
		nav.style.position = 'absolute';
		setTimeout(function(){
			nav.style = '';
			menu.style = '';
			nav.classList.remove('goLeft');
		}, 499);
		menuBtn.innerText = '>';
		
	}
	
}

Base.click = function(){
  var selected = event.target,
	  old_selected = document.getElementsByClassName("selectedLi");
    old_selected[0].classList.remove("selectedLi");
    selected.classList.add("selectedLi");
}