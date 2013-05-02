<?php 
	header("Content-Type: text/html; charset=iso-8859-1");
	session_start();
	if($_GET['mostrar']=="mostrar"){
		//print_r($_GET);
		$tarea=$_GET['tarea'];
		$usuarioComprador=$_GET['usuario'];
		$nivelUsuario=$_GET['nivelUsuario'];
		tareas($tarea,$usuarioComprador,$nivelUsuario);
	}
	if($_GET['action']=="cancelarReq"){
		$val=$_GET['ids'];
		cancelarReq($val);
	}
	if($_GET['action']=="reasignarReq"){
		$val=$_GET['ids'];
		$p=$_GET['p'];
		$s=$_GET['s'];
		$t=$_GET['t'];
		reasignarReq($val,$p,$s,$t);
	}
	if($_GET['action']=="reasignarReq1"){
		$val=$_GET['ids'];
		$p=$_GET['p'];
		$s=$_GET['s'];
		$t=$_GET['t'];
		$usuarioAsig=$_GET['user'];
		reasignarReq1($val,$p,$s,$t,$usuarioAsig);
	}
	if($_GET['action']=="detalleReq"){
		$n=$_GET['n'];
		$usr=$_GET['usr'];
		verDetalle($n,$usr);
	}
	if($_GET['action']=="recalculoReq"){
		$n=$_GET['n'];
		$usr=$_GET['usr'];
		recalcular($n,$usr);
	}
	if($_GET['action']=="recotizaItem"){
		$n=$_GET['n'];
		$req=$_GET['req'];
		recotizarItem($n,$req);
	}
	if($_GET['action']=="cancelaItem"){
		$n=$_GET['n'];
		$req=$_GET['req'];
		cancelarItem($n,$req);
	}
	if($_GET['action']=="verificaReqAutorizadas"){
		$p1=$_GET['p1'];
		$p2=$_GET['p2'];
		$p3=$_GET['p3'];
		verificaReqAutorizadas($p1,$p2,$p3);
	}
	if($_GET['action']=="pFecha"){
		$pFecha=$_GET['pFecha'];
		$id_item=$_GET['id_item'];
		actualizaItemFechaProbable($pFecha,$id_item);
	}
	
	function conectarBd(){
		require("../../includes/config.inc.php");
		$link=mysql_connect($host,$usuario,$pass);
		if($link==false){
			echo "Error en la conexion a la base de datos";
		}else{
			mysql_select_db($db);
			return $link;
		}				
	}
	
//############################################## codigo de Jose G. Ruiz Saenz ...
	if($_POST['array'])
	{
	$array=$_POST['array'];
	$sqlok=0;
	$errores=0;
	$recibidos=0;

function actualizar_db($id_tablarc)
{
$ret=false;
include("../../../clases/adob/adodb.inc.php");
include("../../../includes/config.inc.php");
$bd= ADONewConnection('mysql');
	if(!$bd->Connect($host,$usuario,$pass,$db)){
	echo "Error al conectar la Base de Datos";
	}else{
	//se conecto a la base de datos
		//$sql="SELECT * FROM rc WHERE  asignadaComprador='".$usuarioComprador."' Order by id_rc";
		$sql="UPDATE rc SET status='Autorizando' WHERE id_rc='".$id_tablarc."' ";
			if ($rs=$bd->Execute($sql))
			$ret=true;
	}
return $ret;	
}

	//echo "Proceso: Autorizando <br>";

		$elementos=split(",",$array);
		foreach ($elementos as $valorid)
		{
			if (is_numeric($valorid))
			{ 
			++$recibidos;
			//echo "<br>$valorid "; 
				if (actualizar_db($valorid))
				{
				++$sqlok;
				//echo " Modificado correctamente";
				} else {
				++$errores;
				//echo " Error: No se modifico.";
				}
			}	
		}
		if ($recibidos==$sqlok)
		{
		echo "Los registros fueron modificados correctamente.";
		} else {
		echo "Error: Se registraron errores durante el proceso.<br>Es posible que no todos los registros se modificaran correctamente. ";
		}	
	}
//############################################# codigo de Jose G. Ruiz Saenz ...
	
	function tareas($tarea,$usuarioComprador,$nivelUsuario){
		$status=array(0,'Todas','Nueva','Cotizando','Autorizando','Autorizada','Terminada','Cancelada','busqueda');		
		/*nuevo contenido*/
		$RegistrosAMostrar=25;
		$i=0;
		//estos valores los recibo por GET
		if(isset($_GET['pag'])){
		 	$RegistrosAEmpezar=($_GET['pag']-1)*$RegistrosAMostrar;
		 	$PagAct=$_GET['pag'];
		  //caso contrario los iniciamos
		}else{
		 	$RegistrosAEmpezar=0;
		 	$PagAct=1;
		}
		//consultas a realizar			 
		if(($nivelUsuario==0)||($nivelUsuario==1)){
			if($tarea==1){
				$sqlNueva="SELECT * FROM rc ORDER BY id_rc DESC LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
		 		$sqlNueva1="SELECT * FROM rc Order by id_rc";
			}else if($tarea==8){
				$id=$_GET['parametro'];
				$sqlNueva="select * from rc WHERE id_rc='".$id."' order by id_rc LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
				$sqlNueva1="select * from rc WHERE id_rc='".$id."' order by id_rc";
			}else{
				$sqlNueva="SELECT * FROM rc WHERE status='".$status[$tarea]."' Order by id_rc LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
				$sqlNueva1="SELECT * FROM rc WHERE status='".$status[$tarea]."' Order by id_rc";
			}
		}else if(($nivelUsuario==2)){
			if($tarea==1){
				$sqlNueva="SELECT * FROM rc WHERE asignadaComprador='".$usuarioComprador."' ORDER BY id_rc DESC LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
		 		$sqlNueva1="SELECT * FROM rc WHERE asignadaComprador='".$usuarioComprador."' Order by id_rc";
			}else if($tarea==8){
				$id=$_GET['parametro'];
				$sqlNueva="select * from rc  WHERE id_rc='".$id."' order by id_rc LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
				$sqlNueva1="select * from rc WHERE id_rc='".$id."' order by id_rc";
			}else{
				$sqlNueva="SELECT * FROM rc WHERE status='".$status[$tarea]."' AND asignadaComprador='".$usuarioComprador."' Order by id_rc LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
				$sqlNueva1="SELECT * FROM rc WHERE status='".$status[$tarea]."' AND asignadaComprador='".$usuarioComprador."' Order by id_rc";
			}
		}
		/*echo $sqlNueva;
		echo $sqlNueva1;*/
		$resultadoNueva=mysql_query($sqlNueva,conectarBd());
		$resultadoNueva1=mysql_query($sqlNueva1,conectarBd());
		//******--------determinar las páginas---------******//
		$NroRegistros=mysql_num_rows($resultadoNueva1);//$rs1->RecordCount();
		$PagAnt=$PagAct-1;
		$PagSig=$PagAct+1;
		$PagUlt=$NroRegistros/$RegistrosAMostrar;			
		//verificamos residuo para ver si llevará decimales
		$Res=$NroRegistros%$RegistrosAMostrar;
		// si hay residuo usamos funcion floor para que me devuelva la parte entera, SIN REDONDEAR, y le sumamos una unidad para obtener la ultima pagina
		if($Res>0) $PagUlt=floor($PagUlt)+1;
?>     
		<div align="left" style="margin-left:0px;">
			<form name="frm_cotizando" id="frm_contenedor">
			<table width="900" border="0" cellspacing="1" cellpadding="1" style="font-size: 10px;">			
				<tr>
					<td colspan="8" align="left">
						<div style="border:#CCC solid thin; background-color:#F0F0F0; height:25px;">
						<div style="padding-top:4px; margin-left:4px;float:left; width:auto;">
<?			if ($status[$tarea]=='Cotizando') { 
				echo "[ <a href=\"javascript:autorizar(3,'".$_SESSION['nombre'].".".$_SESSION['apellido']."','Todas');\">Por Autorizar</a> ]";
			} 
			if($status[$tarea]=='Nueva'){
?>
				[ <a href="javascript:reasignar('2','<?=$_SESSION['nombre'].".".$_SESSION['apellido'];?>','Nuevas','reasignacion')">Reasignar</a> ]					
<? 			}
			if($status[$tarea]=='Todas'){
?>
				[ <a href="javascript:cancelar('1','<?=$_SESSION['nombre'].".".$_SESSION['apellido'];?>','Todas')">Cancelar</a> ]
				[ <a href="javascript:reasignar('1','<?=$_SESSION['nombre'].".".$_SESSION['apellido'];?>','Todas','reasignacion')">Reasignar</a> ]	
<?
			}
			if($status[$tarea]=='Autorizada'){
?>	 
				[ <a href="javascript:verificaReqAutorizadas('5','<?=$_SESSION['nombre'].".".$_SESSION['apellido'];?>','Autorizadas')">Verificar Requisiciones</a> ]
<?			}
?>
						</div>
						<div style="float:right; width:auto;">
							Buscar Requisici&oacute;n <input type="text" name="txtBuscaPanel" id="txtBuscaPanel" />
							<input type="button" value="Buscar" id="btnBuscaPanel" onclick="buscaPanel('1','<?=$_SESSION['nombre'].".".$_SESSION['apellido'];?>','Todas')" style="border:#CCC solid thin; background-color:#FFF;" />                            
						</div>                        
						</div>
					</td>
				</tr>
				<tr>
				  <td colspan="8" align="left">&nbsp;</td>
				</tr>  
				<tr>
					<td colspan="8" align="left">
					<a href="javascript:Pagina('1','<?=$usuarioComprador;?>','<?=$tarea;?>')" title="Primero" style="cursor:pointer;">Primero</a>
<?
			if($PagAct>1){ 
?>
				<a href="javascript:Pagina('<?=$PagAnt;?>','<?=$usuarioComprador;?>','<?=$tarea;?>')" title="Anterior" style="cursor:pointer;">Anterior</a>
<?
			}
			echo "<strong>Pagina ".$PagAct."/".$PagUlt."</strong>";
			if($PagAct<$PagUlt){
?>
				<a href="javascript:Pagina('<?=$PagSig;?>','<?=$usuarioComprador;?>','<?=$tarea;?>')" title="Siguiente" style="cursor:pointer;">Siguiente</a>
<?
			}
?>     
				<a href="javascript:Pagina('<?=$PagUlt;?>','<?=$usuarioComprador;?>','<?=$tarea;?>')" title="Ultimo" style="cursor:pointer;">Ultimo</a>
					</td>				 				                     
				</tr>
				<tr>
					<td colspan="8"><div align="right" style="width:auto; float:left; margin-top:6px;" class="style8">Mostrando: <?=$RegistrosAMostrar;?> Registros</div></td>
				</tr>
				<tr>
					<td bgcolor="#CCCCCC">&nbsp;</td>
					<td width="34" bgcolor="#CCCCCC" class="colorNegro" align="center"><strong>Num</strong></td>
					<td width="361" bgcolor="#CCCCCC" class="colorNegro" align="left"><strong>&Aacute;rea</strong></td>
					<td width="87" bgcolor="#CCCCCC" class="colorNegro" align="center"><strong>Status</strong></td>
					<td width="109" bgcolor="#CCCCCC" class="colorNegro" align="center"><strong>Comprador</strong></td>
					<td width="83" bgcolor="#CCCCCC" class="colorNegro" align="center"><strong>Fecha</strong></td>
					<td width="74" bgcolor="#CCCCCC" class="colorNegro">&nbsp;</td>
					<td width="107" bgcolor="#CCCCCC" class="colorNegro">&nbsp;</td>
				</tr>
				<tr>
<?
					$color="#FFFFFF";
					$i=1;
					while($row=mysql_fetch_array($resultadoNueva)){
						$idCambio="idCambio".$i;
						if ($status[$tarea]=='Cotizando') {
							//llamar funcion para hacer la verificacion
							$id_R=$row['id_rc'];
							$totalReq=contarReq($id_R);
							$tot_F=verificarReq($id_R);
						}
?>
						<tr id="<?=$idCambio;?>" style="background-Color:<?=$color;?>;" onMouseOver="anterior=this.style.backgroundColor;this.style.backgroundColor='#D5EAFF'" onmouseout="this.style.backgroundColor=anterior">
							<td style="height:25px;" width="20"><input name="cb" type="checkbox" value="<?=$row['id_rc'];?>" id="<?=$row['id_rc'];?>" onClick="if(this.checked == true){seleccionaFila('<?=$idCambio;?>')} else{seleccionaFila1('<?=$idCambio;?>','<?=$color;?>')}" /></td>
							<td style="height:25px;" class="style10" align="center"><?=$row['id_rc'];?></td>
							<td style="height:25px;" class="style10" align="left">&nbsp;<?=$row['area'];?></td>
							<td style="height:25px;" class="style10" align="left">&nbsp;
<?
							if($row['status']=="Cancelada"){
								echo "<span class='colorRojo'><strong>".$row['status']."</strong></span>";
							}else{
								echo $row['status'];
							}
?>				  
							</td>
							<td style="height:25px;" class="style10" align="left">&nbsp;<?=$row['asignadaComprador'];?></td>    
							<td style="height:25px;" align="center">&nbsp;<?=$row['fecha'];?></td>
							<td style="height:25px;" class="style10" align="center">&nbsp;
<?
						if ($status[$tarea]=='Cotizando') {
							if($tot_F==0){
								echo "<span class='colorRojo'><strong>Autorizar</strong></span>";
							}else{
								echo "<span class='fpequeña'><strong>Faltan: ".$tot_F." prod(s)</strong></span>";
							}
						}
?>	
							</td>
							<td style="height:25px;" align="center">&nbsp;<a href="javascript:abreVentana('<?=$row['id_rc'];?>','<?=$row['status'];?>','<?=$usuarioComprador;?>')" title="Ver la Informaci&oacute;n detallada de este Proveedor" class="Estilo51"><img src="../../img/detalle.png" border="0" longdesc="Detalle de la Requisicion de Compra" /></a> | <a href="../impRq.php?n=<?=$row['id_rc'];?>" target="_blank"><img src="../../img/print-icon.png" border="0" longdesc="Impresion de Requisicion de Compra" /></a></td>
						</tr>	
<?
						($color=="#F0F0F0") ? $color="#ffffff" : $color="#F0F0F0";
						$i=$i+1;
					}
			?>				
				<tr>
					<td colspan="8"><hr color="#000000"></td>
				</tr>
			</table><div id="div_msg" align="center" class=""></div>
			</form>
		</div>
<?            			
	}
	/*funcion que cuenta los items de la req*/
	function contarReq($id_R){		
		$sql="select count(*) as totalReq from items where id_rc='".$id_R."'";				
		//contamos el total de items de la requisicion
		$rs=mysql_query($sql,conectarBd());
		$fila=mysql_fetch_array($rs);
		//extraemos el total de la Req solicitada
		$totalReq=$fila['totalReq'];
		return $totalReq;
	}
	
	function verificarReq($id_R){		
		$sqlx="SELECT count(*) AS tot FROM items WHERE id_rc='".$id_R."' AND costoUnitario='--'";
		//contamos el total de items de la requisicion
		$rs1=mysql_query($sqlx,conectarBd());
		$fila=mysql_fetch_array($rs1);
		//extraemos el total de los items
		$tot=$fila['tot'];
		return $tot;
	}
	//funcion que cancela la requisicion de compra
	function cancelarReq($val){
		include("../../../clases/adob/adodb.inc.php");
		include("../../../includes/config.inc.php");
		$ct=0;
		$et=0;
		$elementos=explode(",",$val);
		$bd2= ADONewConnection('mysql');
		if(!$bd2->Connect($host,$usuario,$pass,$db)){
			echo "Error al conectar la Base de Datos";
		}else{
			for($i=0;$i<count($elementos);$i++){
				$sql_cancel="update rc set status='Cancelada',statusgral='Cancelada' where id_rc='".$elementos[$i]."'";
				$rs2=$bd2->Execute($sql_cancel);
				if($rs2){
					$ct+=1;	
				}else{
					$et+=1;
					echo "ocurrieron errores: ".$et." en la aplicacion.";
				}
			}
			echo "<br><img src='../../../img/images.jpeg' width='50' height='50' longdesc='informacion' />Se han cancelado: ".$ct." Requisicion(es) correctamente.";
		}
		$rs2->Close();
		$bd2->Close();	
	}
	//primera funcion para la reasignacion de las Req, muestra el listado de usuario a los que se les puede asignar
	function reasignarReq($val,$p,$s,$t){
		include("../../../clases/adob/adodb.inc.php");
		include("../../../includes/config.inc.php");
		echo "<br>Seleccione a la persona que desea reasignar la(s) Requisici&oacute;n(es):<br>";
		$bd2= ADONewConnection('mysql');
		if(!$bd2->Connect($host,$usuario,$pass,$db)){
			echo "Error al conectar la Base de Datos";
		}else{
			$sql_comp="select * from compradores order by nombre";
			$rs3=$bd2->Execute($sql_comp);
			echo "<br>";
			while($fila=$rs3->FetchNextObject()){
?>
				&nbsp;&nbsp;[ <a href="javascript:asignaReq('<?=$val;?>','<?=$p;?>','<?=$s;?>','<?=$t;?>','<?=$fila->NOMBRE.".".$fila->APELLIDO?>')"><?=$fila->NOMBRE.".".$fila->APELLIDO?></a> ]<br /><br />
<?				
			}
		}
		$rs3->Close();
		$bd2->Close();
	}
	//funcion que completa el proceso de reasignacion de la Req
	function reasignarReq1($val,$p,$s,$t,$usuarioAsig){
		include("../../../clases/adob/adodb.inc.php");
		include("../../../includes/config.inc.php");
		$ct=0;
		$et=0;
		$elementos1=explode(",",$val);
		$bd3= ADONewConnection('mysql');
		if(!$bd3->Connect($host,$usuario,$pass,$db)){
			echo "Error al conectar la Base de Datos";
		}else{
			for($i=0;$i<count($elementos1);$i++){
				$sql_cancel="update rc set asignadaComprador='".$usuarioAsig."' where id_rc='".$elementos1[$i]."'";
				echo "<br>";
				$rs2=$bd3->Execute($sql_cancel);
				if($rs2){
					$ct+=1;	
				}else{
					$et+=1;
					echo "ocurrieron errores: ".$et." en la aplicacion.";
				}
			}
			echo "<br><img src='../../../img/images.jpeg' width='50' height='50' longdesc='informacion' />Se han enviado: ".$ct." Requisicion(es) correctamente a: ".$usuarioAsig;
		}
		$rs2->Close();
		$bd3->Close();
	}
	
	function verDetalle($n,$usr){
		include("../../../clases/adob/adodb.inc.php");
		include("../../../includes/config.inc.php");
		$bd= ADONewConnection('mysql');
		if(!$bd->Connect($host,$usuario,$pass,$db)){
			echo "Error al conectar la Base de Datos";
		}else{
			$sql="SELECT * FROM rc WHERE  id_rc='".$n."' ";
			$rs=$bd->Execute($sql);
			while($fila=$rs->FetchNextObject()){
				$solicitante=$fila->ID_USUARIO;
				$fecha=$fila->FECHA;
				$hora=$fila->HORA;
				$area=$fila->AREA;
				$cargo=$fila->CARGO;
				$cotizacion=$fila->COTIZACION;
				$rohs=$fila->ROHS;
				$nextel=$fila->NEXTEL;
				$justificacion=$fila->JUSTIFICACION;
				$status=$fila->STATUS;
				$totalReq=$fila->TOTALREQ;
				$obsComprador=$fila->OBSERVACIONES;
			}
			$rs->Close();
			$bd->Close();
		}		
?>
<?
			include("../../../clases/adob/adodb.inc.php");
			include("../../../includes/config.inc.php");
			$bd= ADONewConnection('mysql');
			if(!$bd->Connect($host,$usuario,$pass,$db)){
				echo "Error al conectar la Base de Datos";
			}else{
				$sql="SELECT nombre,apaterno FROM userdbcompras WHERE  id='".$solicitante."' ";
				$rs=$bd->Execute($sql);
				while($filaNombre=$rs->FetchNextObject()){
					$mombreSolicitante=$filaNombre->NOMBRE." ".$filaNombre->APATERNO;
				}
			}			
?>
<table border="0" width="665" align="center" cellspacing="1" cellpadding="1" style="font-family:Verdana, Geneva, sans-serif; font-size:12px;">
  <tr>
	<td colspan="2" style="height:30px;">
    	<div style="float:left;">
        	<strong>Opciones:</strong>
            <a href="javascript:abreVentana('<?=$n;?>','<?=$status;?>','<?=$usr;?>')">Actualizar</a> |
            <a href="javascript:totalizarReq('<?=$n;?>','<?=$status;?>','<?=$usr;?>')">Totalizar Requisici&oacute;n</a>
        </div>
        <div style="float:right;"><a href="javascript:cerrarVista('<?=$usr;?>')">Cerrar Vista Previa</a></div>
    </td>
  </tr>  
  <tr>
    <td colspan="2" style="border:1px solid #999; background:#CCC;">
<span class="Estilo56">
<?
		if($rohs==1)
			$chk='checked="checked"';
		else
			$chk='';
?>	
                <input name="rohs" type="checkbox" id="rohs" value="1" <?=$chk;?>/>ROHS
<?
		if($nextel==1)
			$chk='checked="checked"';
		else
			$chk='';
?>	
                <input name="nextel" type="checkbox" id="nextel" value="1" <?=$chk;?>/>NEXTEL(Partes de Radios)
		
<?
		if($cotizacion==1)
			$chk='checked="checked"';
		else
			$chk='';
?>	
            <input name="chkcot" type="checkbox" id="chkcot" value="1" <?=$chk;?> />
          Solo Cotizar</span>            
    </td>
  </tr>
  <tr>
    <td width="126" style="border:1px solid #CCC; background:#f0f0f0;"># Requisici&oacute;n</td>
    <td width="526" class="Estilo1" style=" border-left:1px solid #CCC; border-bottom:1px solid #CCC;"><strong class="Estilo2"><span class="Estilo2"><?=$n;?></span></strong><input type="hidden" name="numReq" value="<?=$n;?>" /></td>
  </tr>
  <tr>
  	<td style="border:1px solid #CCC; background:#f0f0f0; height:25px;">Fecha / Hora</td>
    <td style=" border-left:1px solid #CCC; border-bottom:1px solid #CCC;">&nbsp;<?=$fecha." - ".$hora;?></td>
  </tr>
  <tr>
    <td style="border:1px solid #CCC; background:#f0f0f0; height:25px;">Status</td>
    <td style=" border-left:1px solid #CCC; border-bottom:1px solid #CCC;">&nbsp;<?=$status;?></td>
  </tr>
  <tr>
    <td style="border:1px solid #CCC; background:#f0f0f0; height:25px;">Solicitante</td>
    <td style=" border-left:1px solid #CCC; border-bottom:1px solid #CCC;">&nbsp;<?=$mombreSolicitante?></td>
  </tr>
  <tr>
    <td style="border:1px solid #CCC; background:#f0f0f0; height:25px;">Area</td>
    <td style=" border-left:1px solid #CCC; border-bottom:1px solid #CCC;">&nbsp;<?=$area; ?></td>
  </tr>
  <tr>
    <td style="border:1px solid #CCC; background:#f0f0f0; height:25px;">Cargo</td>
    <td style=" border-left:1px solid #CCC; border-bottom:1px solid #CCC;">&nbsp;<?=$cargo;?></td>
  </tr>
  <tr>
    <td colspan="2" style="border:1px solid #CCC; background:#f0f0f0; height:25px;">Justificaci&oacute;n&nbsp;<?="--".$justificacion;?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>

<table width="665" border="0" align="center" cellspacing="1">
<?
	include("../../../clases/adob/adodb.inc.php");
	include("../../../includes/config.inc.php");
	include("../../../includes/conectarbase.php");
	$bd= ADONewConnection('mysql');
	if(!$bd->Connect($host,$usuario,$pass,$db)){
		echo "Error al conectar la Base de Datos";
	}else{
		$sql_Items="select * from items where id_rc='".$n."'";
		$rs1=$bd->Execute($sql_Items);
		$i=0;$color="#FFFFFF";
		while($fila1=$rs1->FetchNextObject()){
			$sql_ordenCompra="select id_oc,noOc from oc where id_oc='".$fila1->ID_OC."'";
			$resultNoOc=mysql_db_query($db,$sql_ordenCompra);
			$filaResultOc=mysql_fetch_array($resultNoOc);
?>
      <tr>
      	<td colspan="7">
        <div style=" border:#CCC solid thin; background-color:#CCCCCC; margin-top:4px;">
        <table width="98%" border="0" cellpadding="1" cellspacing="0" align="center" style="border:#CCC solid thin; margin-top:4px; margin-bottom:4px; background-color:#FFF;">
          <tr>
            <td width="106" class="Estilo63" style="height:25px;border-bottom:#999 solid thin;background-color:#F0F0F0;">&nbsp;<strong><?=$fila1->COTSTATUS;?></strong></td>
            <td width="547" class="Estilo63" align="right" style=" border-bottom:#999 solid thin;"><div style="margin-right:5px;"><a href="javascript:probableFecha('<?=$fila1->ID_ITEM;?>','<?=$fila1->DESCRIPCION;?>')">Capturar Probable Fecha de Arribo</a> | <a href="javascript:recotizaItem('<?=$fila1->ID_ITEM;?>','<?=$fila1->ID_RC;?>')" title="Recotizar Item">Recotizar</a> | <a href="javascript:cancelaItem('<?=$fila1->ID_ITEM;?>','<?=$fila1->ID_RC;?>')" title="Cancelar Item">Cancelar Item</a></div></td>
          </tr>
          <tr>
            <td class="Estilo63" style="height:25px; border-bottom:#999 solid thin;">O. Compra</td>
            <td class="Estilo63" style="height:25px; border-bottom:#999 solid thin;"><strong><?=$filaResultOc['noOc'];?></strong></td>
          </tr>        
          <tr>
            <td class="Estilo63" style="height:25px; border-bottom:#999 solid thin;">Cantidad</td>
            <td class="Estilo63" style="height:25px; border-bottom:#999 solid thin;"><?=$fila1->CANTIDAD;?></td>
          </tr>
          <tr>
            <td class="Estilo63" style="height:25px; border-bottom:#999 solid thin;">Unidad</td>
            <td class="Estilo63" style="height:25px; border-bottom:#999 solid thin;"><?=$fila1->UNIDAD;?></td>
          </tr>
          <tr>
            <td class="Estilo63" style="height:25px; border-bottom:#999 solid thin;">Clave P.</td>
            <td class="Estilo63" style="height:25px; border-bottom:#999 solid thin;"><?=$fila1->ID_PRODUCTO;?></td>
          </tr>
          <tr>
            <td class="Estilo63" style="height:25px; border-bottom:#999 solid thin;">Descripci&oacute;n</td>
            <td class="Estilo63" style="height:25px; border-bottom:#999 solid thin;"><?=$fila1->DESCRIPCION;?></td>
          </tr>
          <tr>
            <td class="Estilo63" style="height:25px; border-bottom:#999 solid thin;">Autorizaci&oacute;n</td>
         <?
		if($fila1->AUT_STATUS=="no"){
			$color1="#CC0000";
		}else{$color1="#CCCCCC";}
		?>
            <td bgcolor="<?=$color1;?>" class="Estilo63" style="height:25px; border-bottom:#999 solid thin;"><strong><?=strtoupper($fila1->AUT_STATUS);?></strong></td>
          </tr>
          <tr>
            <td class="Estilo63" style="height:25px; border-bottom:#999 solid thin;">Costo Unitario</td>
            <td class="Estilo63" style="height:25px; border-bottom:#999 solid thin;"><?=$fila1->COSTOUNITARIO;?></td>
          </tr>
          <tr>
            <td class="Estilo63" style="height:25px; border-bottom:#999 solid thin;">Costo Total</td>
            <td class="Estilo63" style="height:25px; border-bottom:#999 solid thin;"><?=$fila1->UNICANTIDAD;?></td>
          </tr>
          <tr>
            <td class="Estilo63" style="height:25px; border-bottom:#999 solid thin;">Fecha P. Entrega</td>
            <td class="Estilo63" style="height:25px; border-bottom:#999 solid thin;"><?=$fila1->FECHA_ARRIBO;?></td>
          </tr>          
        </table>
        </div>
        </td>
  </tr>
<?
		if ($color=="#F0F0F0") 
			$color="#FFFFFF";
		else 
			$color="#F0F0F0";
		$i=$i+1;
		}
	}
?>
      <tr>
	  	<td colspan="5">&nbsp;</td>
		<td width="113" bgcolor="#333333" class="Estilo1"><div align="right" class="style8"><div align="center">Total</div></div></td>
		<td width="101" bgcolor="#FFFFFF" align="center" style="font-family:Verdana, Geneva, sans-serif; font-size:14px;"><?=$totalReq;?></td>
	  </tr>
	  <tr>
	  	<td colspan="7" bgcolor="#CCCCCC" class="Estilo56">Historial de Observaciones</td>
	  </tr>
	  <tr>
        <td colspan="7">
        <div class="Estilo50" style="overflow:auto; height:90px; border-style:solid; border-width:thin;">
		<?
			//se recorre la cadena para separarla en fragmentos
			$a=explode("|",$obsComprador);
			for($i=0;$i<count($a);$i++){
				echo $a[$i]."<br>";
			}
		?>
        </div>		</td>
      </tr>
<!--      <tr>
        <td colspan="7" bgcolor="#FFFFFF"><div align="right"><input name="btnCerrar" value="Cerrar Ventana" onclick="cierraVentana()" type="button" style="border:#CCC solid thin; background-color:#F0F0F0;" /></div></td>
      </tr>-->
      <tr>
      	<td colspan="7">
        <div style="width:85px; height:15px; background-color:#CCCCCC; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;">Autorizados</div>
        <div style="width:85px; height:15px; background-color:#CC0000; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px; color:#FFFFFF">No Autorizados</div>        
        </td>
      </tr>
</table>
<!--</div>
</div>-->
<?	
	}
	
	function recotizarItem($n,$req){
		include("../../../clases/adob/adodb.inc.php");
		include("../../../includes/config.inc.php");
		$bd= ADONewConnection('mysql');
		if(!$bd->Connect($host,$usuario,$pass,$db)){
			echo "Error al conectar la Base de Datos";
		}else{
?>			
			<div id="desv">
				<div id="msg">
    			<div style="border:#CCCCCC solid thin; background-color:#CCCCCC; height:17px; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;"><strong>IQe Sisco Requisiciones de Compra</strong></div>
<?
            $sql_recotizaItem="UPDATE items set aut_status='--',cotStatus='No Cotizado' where id_item='".$n."'";
			$sql_actReq="UPDATE rc set status='Cotizando' where id_rc='".$req."'";
			$rs=$bd->Execute($sql_recotizaItem);
			$rs1=$bd->Execute($sql_actReq);
			if(($rs=true) and ($rs1==true)){
?>
            <div id="msgErrorRecibeOc" style=" display:block;border:#CCCCCC solid thin; background-color:#F0F0F0; height:30px; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:14px;">
           	  <div style="padding-top:5px; margin-left:5px;"><strong>Cambios Realizados</strong><br /><br />
                Nota: Cuando coloque los nuevos datos en el Item es necesario volver a Mandar a Autorizar  la Requisici&oacute;n.<br /><br />
                La requisici&oacute;n <?=$req;?> se encuentra en este momento en Cotizaci&oacute;n.<br /><br />
                Presione la tecla Esc para cerrar esta ventana.                
                </div>
            </div>
<?			
			}else{
?>
            <div id="msgErrorRecibeOc" style=" display:block;border:#CCCCCC solid thin; background-color:#F0F0F0; height:30px; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:14px;">
           	  <div style="padding-top:5px; margin-left:5px;"><strong>Ocurrieron errores al Actualizar los datos</strong><br /><br />
              Busque de nueva cuenta la Requisici&oacute;n <?=$req;?> y verifique la coherencia de los datos.<br /><br />
                Presione la tecla Esc para cerrar esta ventana.                              
                </div>
            </div>
<?			
			}
?>			
            	</div>
            </div>    
<?            
		}
	}
	
	function cancelarItem($n,$req){
		include("../../../clases/adob/adodb.inc.php");
		include("../../../includes/config.inc.php");
		$bd= ADONewConnection('mysql');
		if(!$bd->Connect($host,$usuario,$pass,$db)){
			echo "Error al conectar la Base de Datos";
		}else{
?>			
			<div id="desv">
				<div id="msg">
    			<div style="border:#CCCCCC solid thin; background-color:#CCCCCC; height:17px; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;"><strong>IQe Sisco Requisiciones de Compra</strong></div>
<?
            echo $sql_recotizaItem="UPDATE items set aut_status='--',cotStatus='Cancelado' where id_item='".$n."'";
			//$sql_actReq="UPDATE rc set status='Cotizando' where id_rc='".$req."'";
			$rs=$bd->Execute($sql_recotizaItem);
			//$rs1=$bd->Execute($sql_actReq);
			if($rs=true){
?>
            <div id="msgErrorRecibeOc" style=" display:block;border:#CCCCCC solid thin; background-color:#F0F0F0; height:30px; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:14px;">
           	  <div style="padding-top:5px; margin-left:5px;"><strong>Cambios Realizados</strong><br /><br />
                Nota: El Item de la Requisici&oacute;n <?=$req;?> se ha cancelado.<br /><br />
                Presione la tecla Esc para cerrar esta ventana.                
              </div>
            </div>
<?			
			}else{
?>
            <div id="msgErrorRecibeOc" style=" display:block;border:#CCCCCC solid thin; background-color:#F0F0F0; height:30px; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:14px;">
           	  <div style="padding-top:5px; margin-left:5px;"><strong>Ocurrieron errores al Actualizar los datos</strong><br /><br />
              Busque de nueva cuenta la Requisici&oacute;n <?=$req;?> y verifique la coherencia de los datos.<br /><br />
                Presione la tecla Esc para cerrar esta ventana.                              
                </div>
            </div>
<?			
			}
?>			
            	</div>
            </div>    
<?            
		}	
	}
	
	function verificaReqAutorizadas($p1,$p2,$p3){
		include("../../../clases/adob/adodb.inc.php");
		include("../../../includes/config.inc.php");
		$bd= ADONewConnection('mysql');
		if(!$bd->Connect($host,$usuario,$pass,$db)){
			echo "Error al conectar la Base de Datos";
		}else{
?>

			<div id="msg1">
    			<div style="border:#000 solid thin; background-color:#000; color:#FFF; height:17px; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;"><strong>IQe Sisco Requisiciones de Compra - Actualizaci&oacute;n...</strong></div>
                <div style="height:200px;font-family:Verdana, Geneva, sans-serif; font-size:12px; margin:4px; overflow:auto; border:#CCC solid thin;">
                <br />
<?                
                $sql_busca_autorizadas="select id_rc from rc where status='Autorizada' order by id_rc";				
				$rs_busca=$bd->Execute($sql_busca_autorizadas);
				while($filaBusca=$rs_busca->FetchNextObject()){
					$total=$filaBusca->TOTAL_AUTORIZADAS;
					$ids[]=$filaBusca->ID_RC;
				}
				/*for($i=0;$i<count($ids);$i++){
					echo $ids[$i]."<br>";
				}*/
				for($i=0;$i<count($ids);$i++){
					$sql_statusReq="SELECT status FROM rc WHERE id_rc='".$ids[$i]."'";
					//echo "<br>";
					$sql_totalRecibidos="SELECT COUNT(*) as total_recibidos from items WHERE entregadoAlm = 'Recibido' AND id_rc='".$ids[$i]."'";
					//echo "<br>";
					$sql_total_items_req="SELECT COUNT(*) as total_items FROM items WHERE id_rc='".$ids[$i]."'";
					//echo "<br>";
					$sql_total_enOc="SELECT COUNT(*) as total_oc FROM items WHERE id_rc ='".$ids[$i]."' AND id_oc <> 0";
					//echo "<br>";
					$sql_total_cancelados="SELECT COUNT(*) as total_cancela FROM items WHERE cotStatus ='Cancelado' AND id_rc ='".$ids[$i]."'";
					//echo "<br>";
					$rsStatusReq=$bd->Execute($sql_statusReq);
					$rsItemsRecibidos=$bd->Execute($sql_totalRecibidos);
					$rsTotalItemsReq=$bd->Execute($sql_total_items_req);
					$rsTotalOc=$bd->Execute($sql_total_enOc);
					$rsTotalCancelados=$bd->Execute($sql_total_cancelados);
					while($filaStatusReq=$rsStatusReq->FetchNextObject()){
						$statusReq=$filaStatusReq->STATUS;
					}
					while($filaTotalRecibidos=$rsItemsRecibidos->FetchNextObject()){
						$totalRecibidos=$filaTotalRecibidos->TOTAL_RECIBIDOS;
					}
					while($filaTotalItems=$rsTotalItemsReq->FetchNextObject()){
						$totalItemsReq=$filaTotalItems->TOTAL_ITEMS;
					}
					while($filaTotalOc=$rsTotalOc->FetchNextObject()){
						$totalTotalOc=$filaTotalOc->TOTAL_OC;
					}
					while($filaTotalCancelados=$rsTotalCancelados->FetchNextObject()){
						$totalCancelados=$filaTotalCancelados->TOTAL_CANCELA;
					}
					$suma=$totalTotalOc+$totalCancelados;
					echo "--------------------------------------<br>";
					if(($suma==$totalItemsReq) && ($statusReq=="Autorizada") && ($totalRecibidos==$totalItemsReq)){
						$sql_actualizaReqTerminada="UPDATE rc SET status='Terminada',statusgral='Terminada' WHERE id_rc='".$ids[$i]."'";
						$rsActualizaReq=$bd->Execute($sql_actualizaReqTerminada);
						//$rsActualizaReq=true;
						if($rsActualizaReq==true){
							echo "<img src='../../../img/check1.jpg' />Requisici&oacute;n Actualizada<br>";
						}else{
							echo "<br><strong>Error al Actualizar la Requisici&oacute;n</strong><br>";
						}
					}					
					echo "Requisicion: ".$ids[$i];
					echo "<br>";
					echo "Status: ".$statusReq;
					echo "<br>";
					echo "items de la Requisicion: ".$totalItemsReq;
					echo "<br>";
					echo "items en orden de Compra: ".$totalTotalOc;
					echo "<br>";
					echo "items cancelados: ".$totalCancelados;
					echo "<br>";
					echo "items recibidos: ".$totalRecibidos;
					echo "<br>--------------------------------------<br>";					
				}
?>				
                </div>
                <div style="margin-left:40%;width:100px;border:#CCC solid thin; background-color:#FFF;"><a href="javascript:cerrarDialogo('<?=$p1;?>','<?=$p2;?>','<?=$p3;?>')" class="Estilo50" style="color:#000; text-decoration:none;">Cerrar Ventana</a></div>
            </div>
<?		
		}
	}
	
	function actualizaItemFechaProbable($pFecha,$id_item){
		include("../../../clases/adob/adodb.inc.php");
		include("../../../includes/config.inc.php");
		$bd= ADONewConnection('mysql');
		if(!$bd->Connect($host,$usuario,$pass,$db)){
			echo "Error al conectar la Base de Datos";
		}else{
			$sql_actualizaItemFecha="UPDATE items set fecha_arribo='".$pFecha."' WHERE id_item='".$id_item."'";
			$rs=$bd->Execute($sql_actualizaItemFecha);
			if($rs){
				echo "<br>Se ha colocado la Fecha de Arribo Correctamente<br>";
			}else{
				echo "Han ocurrido errores en la Actualizacion de los Datos";
			}
		}
	}
	
	function recalcular($n,$usr){
		include("../../../includes/config.inc.php");
		include("../../../includes/conectarbase.php");
		$sql_extrae="select uniCantidad from items where id_rc='".$n."'";
		$result_extrae=mysql_db_query($db,$sql_extrae);
		$totales=0;
		while($fila_extrae=mysql_fetch_array($result_extrae)){
			$totales+=$fila_extrae['uniCantidad'];
		}
		$sqlActReq="update rc set totalReq='".$totales."' where id_rc='".$n."'";
		$resultActReq=mysql_db_query($db,$sqlActReq);
?>		
		<div id="desv">
        	<div id="msg2">
            	<div style="background:#000; height:20px; color:#FFF;"><div style="float:left; font-family:Verdana, Geneva, sans-serif; font-size:12px;">Informaci&oacute;n</div><div style="float:right;"><a href="javascript:cierraInfo('desv','msg2','<?=$n;?>','<?=$usr;?>')"><img src="../../../img/close.gif" border="0" /></a></div></div>
                <div style="font-family:Verdana, Geneva, sans-serif; font-size:16px; text-align:center; padding-top:40px;">
<?
				if($resultActReq==true){
					echo "<img src='../../../img/clean.png' border='0' />&nbsp;&nbsp;Cambios Realizados";
				}else{
					echo "Error, al actualizar los Datos";
				}				
?>
            	</div>
            </div>
        </div>
<?        
	}
?>