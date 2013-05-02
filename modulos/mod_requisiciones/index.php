<?php
    session_start();
    $nombreUsuario=$_SESSION['nombre'].".".$_SESSION['apellido'];
    $nivelUsuario=$_SESSION['usuario_nivel'];
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<link rel="stylesheet" type="text/css" href="../../recursos/tabs/css/tabs.css" />
<script type="text/javascript" src="../../clases/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="../../recursos/tabs/js/tabs.js"></script>
<script type="text/javascript">
	var tab=2;
	$(document).ready(function(){
		mostrarTab('1','contentTab1','funcionesMostrar.php','mostrar=mostrar&usuario=<? echo $nombreUsuario;?>&tarea=1&nivelUsuario=<?=$nivelUsuario;?>','GET');
		verificaMovIzq();		   
		redimensionar();
		$("#left").click(function(){			
		  $("#contenedorTabs").animate({"left": "+=100px"}, "fast");
		  verificaMovIzq();	  
		  //posicionReal = $("#contenedorTabs").offset();
		  //alert(posicionReal.left);
		});
	
		$("#right").click(function(){
		  $("#contenedorTabs").animate({"left": "-=100px"}, "fast");
		  verificaMovDer();  
		});		
		
		
	});	

	window.onresize=redimensionar;
</script>
<style type="text/css">
body{margin: 0;overflow: hidden;}
</style>
</head>

<body>
    <div id="contenedor">
	<div id="contenedorTabs" class="contenedorTabs">
            <div id="tab1" class="bordeDiv">
                <a href="javascript:void(0)" onclick="mostrarTab('1','contentTab1','funcionesMostrar.php','mostrar=mostrar&usuario=<? echo $nombreUsuario;?>&tarea=1&nivelUsuario=<?=$nivelUsuario;?>','GET')" class="enlacesTabs">Todas</a>               
            </div>
            <div id="tab2" class="bordeDiv">
                <a href="javascript:void(0)" onclick="mostrarTab('2','contentTab2','funcionesMostrar.php','mostrar=mostrar&usuario=<?=$nombreUsuario?>&tarea=2s&nivelUsuario=<?=$nivelUsuario;?>','GET')" class="enlacesTabs">Nuevas</a>
            </div>
            <div id="tab3" class="bordeDiv">
                <a href="javascript:void(0)" onclick="mostrarTab('3','contentTab3','funcionesMostrar.php','mostrar=mostrar&usuario=<?=$nombreUsuario?>&tarea=3&nivelUsuario=<?=$nivelUsuario;?>','GET')" class="enlacesTabs">Cotizadas</a>
            </div>
            <div id="tab4" class="bordeDiv">
                <a href="javascript:void(0)" onclick="mostrarTab('4','contentTab4','funcionesMostrar.php','mostrar=mostrar&usuario=<?=$nombreUsuario?>&tarea=4&nivelUsuario=<?=$nivelUsuario;?>&nivelUsuario=<?=$nivelUsuario;?>','GET')" class="enlacesTabs">Por Autorizar</a>
            </div>
            <div id="tab5" class="bordeDiv">
                <a href="javascript:void(0)" onclick="mostrarTab('5','contentTab5','funcionesMostrar.php','mostrar=mostrar&usuario=<?=$nombreUsuario?>&tarea=5&nivelUsuario=<?=$nivelUsuario;?>','GET')" class="enlacesTabs">Autorizadas</a>
            </div>
            <div id="tab6" class="bordeDiv">
                <a href="javascript:void(0)" onclick="mostrarTab('6','contentTab6','funcionesMostrar.php','mostrar=mostrar&usuario=<?=$nombreUsuario?>&tarea=6&nivelUsuario=<?=$nivelUsuario;?>','GET')" class="enlacesTabs">Terminadas</a>
            </div>
            <div id="tab7" class="bordeDiv">
                <a href="javascript:void(0)" onclick="mostrarTab('7','contentTab7','funcionesMostrar.php','mostrar=mostrar&usuario=<?=$nombreUsuario?>&tarea=7&nivelUsuario=<?=$nivelUsuario;?>','GET')" class="enlacesTabs">Canceladas</a>
            </div>
            <div id="tab8" class="bordeDiv">
                <a href="javascript:void(0)" onclick="mostrarTab('8','contentTab8','funcionesMostrar.php','mostrar=mostrar&usuario=<?=$nombreUsuario?>&tarea=Nuevas','GET')" class="enlacesTabs">Vista Previa</a>
            </div>            
        </div>
    </div>    
    <input type="button" id="left" class="botonesDesp" value="<" />
    <input type="button" id="right" class="botonesDesp" value=">" />
    <div class="separador"></div>
        <div id="contenedorContenidos">
    </div>
    <div id="contenidoTab">
    	<div id="contentTab1" class="contenidoTabs">
    		Contenido Tab 1<br /><br />
        	<a href="#" onclick="addTab('Biografia Steve Jobs','bio1.html','','GET')">Cargar Biografia 1</a><br /><br />
                <a href="#" onclick="addTab('Biografia Bill Gates','bio2.html','','GET')">Cargar Biografia 2</a>
        </div>
        <div id="contentTab2" class="contenidoTabs"></div>
        <div id="contentTab3" class="contenidoTabs"></div>
        <div id="contentTab4" class="contenidoTabs"></div>
        <div id="contentTab5" class="contenidoTabs"></div>
        <div id="contentTab6" class="contenidoTabs"></div>
        <div id="contentTab7" class="contenidoTabs"></div>
        <div id="contentTab8" class="contenidoTabs"></div>
    </div>
</body>
</html>