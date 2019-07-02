var document = document || {},
    console = console || {},
    window = window || {},
    toggleSection = toggleSection || {};


toggleSection.iniciarBtn = function(){
  var btnEquipo,
  divContenedor;
  window.addEventListener("DOMContentLoaded", function() {
      btnEquipo = document.getElementsByClassName("btnEquipo");
      divContenedor = document.getElementsByClassName("divContenedor");
      toggleSection.addListener(btnEquipo,divContenedor);
  });
}

toggleSection.iniciarBtnSec = function(){
  var btnEquipo,
  divContenedor;
  window.addEventListener("DOMContentLoaded", function() {
    btnEquipo = document.querySelectorAll('.botonesTurnos > div');
    divContenedor = document.querySelectorAll('.contenedorTablaTurnos > div');
    toggleSection.addListener(btnEquipo,divContenedor);
  });
}

toggleSection.addListener = function(btnEquipo,divContenedor){
  for(var i=0; btnEquipo.length > i; i++){
    btnEquipo[i].firstElementChild.addEventListener('click', function (){toggleSection.toggle(btnEquipo,divContenedor)});
  }
  btnEquipo[0].firstElementChild.click();//CASO POR DEFAULT
}

toggleSection.toggle = function(btnEquipo,divContenedor){
  var btn = event.target,
  i = 0,
  find = false;

  while((i<btnEquipo.length) && (!find)){//Busco el event en el array de btn
      if(btnEquipo[i].firstElementChild != btn){
        i++;
      }else {
        find = true;
      }
  }
  for (var j = 0; j < divContenedor.length; j++) {//Recorro los contenedores y los oculto
    if(i == j){
      document.getElementById(divContenedor[j].id).style.display = 'block';
      btnEquipo[j].classList.add("btnActive");
    }else{
      document.getElementById(divContenedor[j].id).style.display = 'none';
      btnEquipo[j].classList.remove("btnActive");
    }
  }
}
