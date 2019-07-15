function cargarAdmin(){
  var head = document.getElementsByTagName("head")[0],
      script = document.createElement("script"),
      title = document.createElement("title"),
      cssAd = document.createElement("link")
      cssTu = document.createElement("link");


title.innerText = "Panel administrador - Turnos Reservados";
head.appendChild(title);

cssAd.rel = "stylesheet";
cssAd.type = "text/css";
cssAd.href = "/assets/css/turnos.css";
head.appendChild(cssAd);

cssTu.rel = "stylesheet";
cssTu.type = "text/css";
cssTu.href = "/assets/css/admin.css";
head.appendChild(cssTu);

  cargarScript("/assets/js/admin.js", function(){
    admin.init();
  });
}

function cargarScript(url, callback){
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
