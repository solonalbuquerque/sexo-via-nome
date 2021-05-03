<!doctype html>
<html lang="pt" prefix="og: http://ogp.me/ns#" class="h-100">
  <head>
    <meta charset="UTF-8"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Teste para capturar o sexo da pessoa">
    <meta name="author" content="solonalbuquerque@gmail.com">
    
    <title>Teste do Sexo via Nome</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">

    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
          
        }
      }
    </style>

  </head>
  <body class="d-flex flex-column h-100">
    
    <main class="flex-shrink-0">
      <div class="container">
        <h1 class="mt-5">Vamos descobrir o Sexo?</h1>
        
        <div class="row">
            <div class="col-12 col-md-4">
                <input type="text" class="form-control p-2" id="nome" placeholder="Qual o seu nome?">
            </div>
            <div class="col-12 col-md-7">
                <button type="button" class="btn btn-primary" id="consultar" />consultar</button>
            </div>
            <div class="col-12 text-muted pt-2">
                <span id="ressexo"></span>
            </div>
        </div>

      </div>
    </main>
    
    <footer class="footer mt-auto py-3 bg-light">
      <div class="container">
        <span class="text-muted pr-3">Feito por Sólon Albuquerque</span>
        <span id="resmessage" class="text-primary"></span>
      </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
    
<script>
var request = null;
var loadbtn = '<div class="spinner-border" role="status"></div>';

function myAjaxFunction(){
    console.log("pre...");
     $.ajaxSetup({cache: false});
     if (request != null) {
        console.log("cancela...");
        request.abort();
        request = null;
     }
     $("#ressexo").html("");
     request = $.post("call.php", {"nome": $("#nome").val() }, function (data) {
         console.log("data", data);
         $("#resmessage").html(data.mensagem);
         $("#resnome").html(data.nome);
         if(data.sexo=="m") {
            $("#ressexo").html('MASCULINO');
         } else if(data.sexo=="f") {
            $("#ressexo").html('FEMININO');
         } else {
            $("#ressexo").html(data.mensagem);
         }
         $("#consultar").html("consultar");
     });
}
$("#consultar").click(function () {
    if($("#nome").val()!="") {
        myAjaxFunction();
        $("#consultar").html(loadbtn);
    }
});
</script>
  </body>
</html>