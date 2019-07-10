var document = document || {},
    console = console || {},
    window = window || {},
    Base = Base || {};

Base.init = function(){
	window.addEventListener("DOMContentLoaded", function(){
    var botonNav = document.getElementsByClassName("botonNav"),
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
		botonNav[initSel].classList.add("selectedLi");
		for (var i = 0; i < botonNav.length; i++) {
			botonNav[i].addEventListener("click",Base.click);
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

function msgConfirmar(msj,titulo,data,accionOk,accionCancelar){
	var ventana = document.createElement("div"),
		overlay = document.createElement("div"),
		h3 = document.createElement("h3"),
		p = document.createElement("p"),
		divBtn = document.createElement("div"),
		okBtn = document.createElement("span"),
		cancelBtn = document.createElement("span"),
		cerrarBtn = document.createElement("span"),
		ventana = document.createElement("div");
	
	ventana.classList.add("ventana");
	overlay.classList.add("overlay");
	overlay.id = "msgConfirmar";
	okBtn.classList.add("boton");
	cancelBtn.classList.add("boton");
	okBtn.classList.add("okBtn");
	cancelBtn.classList.add("cancelBtn");
	cerrarBtn.classList.add("cerrar");
	ventana.classList.add("ventana");
	h3.innerHTML = titulo;
	p.innerHTML = msj;
	okBtn.innerHTML = "Aceptar";
	cerrarBtn.setAttribute("onclick",accionCancelar);
	cancelBtn.setAttribute("onclick",accionCancelar);
	okBtn.setAttribute("onclick",accionOk);
	cancelBtn.innerHTML = "Cancelar";
	
	ventana.appendChild(h3);
	ventana.appendChild(p);
	divBtn.appendChild(cancelBtn);
	divBtn.appendChild(okBtn);
	ventana.appendChild(divBtn);
	ventana.appendChild(cerrarBtn);
	
	overlay.appendChild(ventana);
	
	document.querySelector("body").appendChild(overlay);
	
}
function msgNotificar(msj,titulo){
	var ventana = document.createElement("div"),
		overlay = document.createElement("div"),
		h3 = document.createElement("h3"),
		p = document.createElement("p"),
		divBtn = document.createElement("div"),
		okBtn = document.createElement("span"),
		cerrarBtn = document.createElement("span"),
		ventana = document.createElement("div");
	
	ventana.classList.add("ventana");
	overlay.classList.add("overlay");
	overlay.id = "msgNotificar";
	okBtn.classList.add("boton");
	okBtn.classList.add("okBtn");
	cerrarBtn.classList.add("cerrar");
	ventana.classList.add("ventana");
	h3.innerHTML = titulo;
	p.innerHTML = msj;
	okBtn.innerHTML = "Aceptar";
	okBtn.setAttribute("onclick","cerrarNotificacion()");
	cerrarBtn.setAttribute("onclick","cerrarNotificacion()");
	
	ventana.appendChild(h3);
	ventana.appendChild(p);
	divBtn.appendChild(okBtn);
	ventana.appendChild(divBtn);
	ventana.appendChild(cerrarBtn);
	
	overlay.appendChild(ventana);
	
	document.querySelector("body").appendChild(overlay);
}

function cerrarNotificacion(){
	var msg = document.querySelector("#msgNotificar");
	msg.parentElement.removeChild(msg);
}

Base.click = function(){
  var selected = event.target,
	  old_selected = document.getElementsByClassName("selectedLi");
    old_selected[0].classList.remove("selectedLi");
    selected.classList.add("selectedLi");
}