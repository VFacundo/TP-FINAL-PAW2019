var document = document || {},
    console = console || {},
    window = window || {},
    Base = Base || {};

Base.init = function(){
  window.addEventListener("DOMContentLoaded", function(){
     var botonLi = document.getElementsByClassName("botonLi");
     var initSel = 0;
     var location = window.location.href;
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
  });
}
Base.click = function(){
  var selected = event.target,
	  old_selected = document.getElementsByClassName("selectedLi");
    old_selected[0].classList.remove("selectedLi");
    selected.classList.add("selectedLi");
}
