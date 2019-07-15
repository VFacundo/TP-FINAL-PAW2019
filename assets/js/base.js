var document = document || {},
    console = console || {},
    window = window || {},
    Base = Base || {};


Base.init = function(){
	window.addEventListener("DOMContentLoaded", function(){
    var  botonNav = document.getElementsByClassName("botonNav"),
			   initSel = 0,
			   menu = document.querySelector("#menuBtn"),
			   location = window.location.href,
         head = document.getElementsByTagName("head")[0],
         script = document.createElement("script"),
         title = document.createElement("title"),
         css = document.createElement("link");

		if(location.search("perfil")!=-1){
			initSel = 0;
      title.innerText = "Mi Perfil";
      head.appendChild(title);
      css.rel = "stylesheet";
      css.href = "/assets/css/perfil.css";
      //head.appendChild(css);
      head.insertBefore(css,document.getElementsByTagName("script")[0])
      Base.cargarScript("/assets/js/editarPerfil.js", function(){
      });

		}else if (location.search("equipo")!=-1) {
			initSel = 1;
      title.innerText = "Mi Equipo";
      head.appendChild(title);
      css.rel = "stylesheet";
      css.type = "text/css";
      css.href = "/assets/css/equipo.css";
      //head.appendChild(css);
      head.insertBefore(css,document.getElementsByTagName("script")[0])
      Base.cargarScript("assets/js/crearEquipo.js", function(){
      });
      Base.cargarScript("assets/js/toggleSection.js", function(){
        var toggleBtnEquipo = new toggleSection.iniciarBtn();
      });
      Base.cargarScript("assets/js/instantSearch.js", function(){
         instantSearch.init("j_usr","usuario");
      });
      Base.cargarScript("assets/js/mostrarDetalles.js", function(){
      });
      Base.cargarScript("assets/js/equipo.js", function(){
      });

		}else if (location.search("turnos")!=-1) {
			initSel = 2;
      title.innerText = "Mis Turnos";
      head.appendChild(title);
      css.rel = "stylesheet";
      css.type = "text/css";
      css.href = "/assets/css/turnos.css";
      //head.appendChild(css);
      head.insertBefore(css,document.getElementsByTagName("script")[0])
      Base.cargarScript("assets/js/toggleSection.js", function(){
        var toggleBtnEquipo = new toggleSection.iniciarBtn(),
      					toggleBtnSec = new toggleSection.iniciarBtnSec();
      });
      Base.cargarScript("assets/js/mostrarDetalles.js", function(){
      });
      Base.cargarScript("assets/js/turnos.js", function(){
        turnos.init();
      });
      Base.cargarScript("assets/js/instantSearch.js", function(){
         instantSearch.init("eq_rival","equipo");
      });

		}else if (location.search("buscar")!=-1) {
			initSel = 3;
      title.innerText = "Buscar Partido";
      head.appendChild(title);
      Base.cargarScript("assets/js/mostrarDetalles.js", function(){
      });
      Base.cargarScript("assets/js/desafio.js", function(){
      });

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


Base.initAdmin = function(){
	window.addEventListener("DOMContentLoaded", function(){
    var botonNav = document.getElementsByClassName("botonNav"),
			initSel = 0,
			menu = document.querySelector("#menuBtn"),
			location = window.location.href;
		if(location.search("turnosreservados")!=-1){
			initSel = 0;
		}else if (location.search("canchas")!=-1) {
			initSel = 1;
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
function borrarItemLi(id,from){
	var items = document.querySelectorAll(from),
		salir = false;
	console.log("item",items,"from",from);
	for(var i = 0;i<items.length;i++){
		console.log("item",items[i]);
		for(var j = 0;j<items[i].getElementsByTagName("input").length;j++){
			if((items[i].getElementsByTagName("input")[j].type == "hidden")&&(items[i].getElementsByTagName("input")[j].value == id)){
				var borrar = items[i];
				console.log(borrar,"borrar");
				console.log(borrar.parentElement,"borrar.parentElement");
				borrar.parentElement.removeChild(borrar);
				break;
			}
		}
		if(salir) break;
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

Base.cargarScript = function(url, callback){
  var nuevoScript = document.createElement("script");
  nuevoScript.type = "text/javascript";
  if (nuevoScript.readyState){
    nuevoScript.onreadystatechange = function(){
      if (nuevoScript.readyState == "loaded" || nuevoScript.readyState == "complete"){
        nuevoScript.onreadystatechange = null;
        callback();
      }
    }
  } else {
    nuevoScript.onload = function(){
      callback();
    }
  }
  nuevoScript.src = url;
  document.getElementsByTagName("head")[0].appendChild(nuevoScript);
}
