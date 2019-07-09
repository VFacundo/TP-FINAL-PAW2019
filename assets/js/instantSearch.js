var document = document || {},
    console = console || {},
    window = window || {},
    instantSearch = instantSearch || {};

  instantSearch.init = function(field,tableName){
      var input;
        window.addEventListener("DOMContentLoaded", function() {
          input = document.getElementsByName(field),
          dataList = document.createElement("datalist");
          dataList.id = tableName;
          document.body.appendChild(dataList);
          for (var i = 0; i < input.length; i++) {
            input[i].addEventListener('input', function (){instantSearch.search(tableName)});
            input[i].addEventListener('change', function (){instantSearch.clearData(tableName)});
            input[i].setAttribute("list",tableName);
          }
          console.log(input);
      });
    }

instantSearch.clearData = function(tableName){
  dataList = document.querySelector("#"+tableName);
    while(dataList.firstChild){
      dataList.removeChild(dataList.firstChild);
    }
}

instantSearch.search = function(tableName){
  var target = event.target,
      xhr = new XMLHttpRequest(),
      dataList = document.querySelector("#"+tableName),
      formData = new FormData();

  if((target.value).length>1){
      formData.append("tableName",tableName);
      formData.append("cadena_busqueda",target.value);
      xhr.open('POST', 'http://localhost/instantsearch/');
        xhr.onload = function() {
        dataList.innerHTML = xhr.responseText;
        console.log(xhr.responseText);
        }
      xhr.send(formData);
  }else {
    console.log("NO buscando");
  }

}
