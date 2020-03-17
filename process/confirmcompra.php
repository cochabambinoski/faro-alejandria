<?php
session_start(); 

include '../library/configServer.php';
include '../library/consulSQL.php';
$num=$_POST['clien-number'];
if($num=='notlog'){
   $nameClien=$_POST['clien-name'];
   $passClien=  md5($_POST['clien-pass']);   
}
if($num=='log'){
   $nameClien=$_POST['clien-name'];
   $passClien=$_POST['clien-pass'];
   if(!empty($dirD))
    {
      $dirD=$_POST['conf-dir']; 
    }  
}
sleep(3);

$verdata=  ejecutarSQL::consultar("select * from cliente where Clave='".$passClien."' and Nombre='".$nameClien."'");
$num=  mysql_num_rows($verdata);
if($num>0){
  if($_SESSION['sumaTotal']>0){


  $data= mysql_fetch_array($verdata);
  $nitC=$data['NIT'];
  $StatusV="Pendiente";
  $dirC=$data['Direccion']; 

  if(!empty($dirD))
    {
      $dirC=$_POST['conf-dir']; 
    }

  
    
  /*Insertando datos en tabla venta*/
  if($nameClien=='Local'){
    consultasSQL::InsertSQL("venta", "Fecha, NIT, Direccion, TotalPagar, Estado, Usuario", "'".date('d-m-Y H:i:s')."','".$nitC."','".$dirC."','".$_SESSION['sumaTotal']."','Entregado','".$nameClien."'");
  } else{
  consultasSQL::InsertSQL("venta", "Fecha, NIT, Direccion, TotalPagar, Estado, Usuario", "'".date('d-m-Y H:i:s')."','".$nitC."','".$dirC."','".$_SESSION['sumaTotal']."','".$StatusV."','".$nameClien."'");
  }
  /*recuperando el número del pedido actual*/
  $verId=ejecutarSQL::consultar("select * from venta where NIT='$nitC' order by NumPedido desc limit 1");
  while($fila=mysql_fetch_array($verId)){
     $Numpedido=$fila['NumPedido'];
  }
  
  /*Insertando datos en detalle de la venta*/
  if(isset($_SESSION['contador'])){
  for($i = 0;$i< $_SESSION['contador'];$i++){
      consultasSQL::InsertSQL("detalle", "NumPedido, CodigoProd, CantidadProductos, Fecha", "'$Numpedido', '".$_SESSION['producto'][$i]."', '1', '".date('Y-m-d')."'");

      /*Restando un stock a cada producto seleccionado en el carrito*/
    $prodStock=ejecutarSQL::consultar("select * from producto where CodigoProd='".$_SESSION['producto'][$i]."'");
    while($fila = mysql_fetch_array($prodStock)) {
        $existencias = $fila['Stock'];
        consultasSQL::UpdateSQL("producto", "Stock=('$existencias'-1)", "CodigoProd='".$_SESSION['producto'][$i]."'");
    }
  }
  }
    
    /*Vaciando el carrito*/
    unset($_SESSION['producto']);
    unset($_SESSION['contador']);
    
    echo '<img src="assets/img/ok.png" class="center-all-contens"><br>El pedido se ha realizado con éxito';
  }else{
    echo '<img src="assets/img/error.png" class="center-all-contens"><br>No has seleccionado ningún producto, revisa el carrito de compras';
  }

}else{
    echo '<img src="assets/img/error.png" class="center-all-contens"><br>El nombre o contraseña invalidos';
}
 


