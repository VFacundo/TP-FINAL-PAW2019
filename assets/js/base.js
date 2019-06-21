var document = document || {},
    console = console || {},
    window = window || {},
    Base = Base || {};

Base.init = function(){
  window.addEventListener("DOMContentLoaded", function(){
     var botonLi = document.getElementsByClassName("botonLi");
     var initSel = window.sessionStorage.getItem("selected");
     botonLi[initSel].classList.add("selectedLi");
      for (var i = 0; i < botonLi.length; i++) {
        botonLi[i].addEventListener("click",Base.click);
        botonLi[i].dataset.id = i;
      }

  });
}
Base.click = function(){
  var selected = event.target;
  var old_selected = document.getElementsByClassName("selectedLi");
    old_selected[0].classList.remove("selectedLi");
    selected.classList.add("selectedLi");
    window.sessionStorage.setItem("selected",selected.dataset.id);

}
