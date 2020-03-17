<?php include './inc/navbar.php'; ?>
<?php
    include './library/configServer.php';
    include './library/consulSQL.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Pedido</title>
    <?php include './inc/link.php'; ?>
</head>
<body id="container-page-index">
<script type="text/javascript">
    $(function(){
        $('#info').click(function() {
            $(this).hide();
        });
    });
</script>
    <section id="container-pedido">
        <div class="container">
            <div class="page-header">
              <h1>Confirmar pedido</h1>
            </div>
            <br><br><br>
            <div class="row">
                <div class="col-xs-12 col-sm-6">
                <div id="del-pedido">
                                <h2 class="text-danger text-center">&nbsp;&nbsp;Pedidos Pendientes</h2>
                                <form action="process/delPedido.php" method="post" role="form">
                                    <div class="form-group">
                                        <label>Pedidos</label>
                                        <select class="form-control" name="num-pedido">
                                            <?php
                                                $nameClien=$_SESSION['nombreUser']; 
                                                $pedidoC=  ejecutarSQL::consultar("select * from venta where Usuario='".$nameClien."' and estado='Pendiente'");
                                                while($pedidoD=mysql_fetch_array($pedidoC)){
                                                    echo '<option value="'.$pedidoD['NumPedido'].'">Pedido #'.$pedidoD['NumPedido'].' - Estado('.$pedidoD['Estado'].') - Fecha('.$pedidoD['Fecha'].')</option>';
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="table-responsive">
                                  <table class="table table-bordered-2">
                                      <thead class="">
                                          <tr>
                                              <th class="text-center">#</th>
                                              <th class="text-center">Producto</th>
                                              <th class="text-center">Marca</th>
                                              <th class="text-center">Modelo</th>
                                              <th class="text-center">Precio</th>
                                              <th class="text-center">Cantidad</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                          <?php
                                            if(!$_SESSION['nombreAdmin']==""){
                                                $pedidoU=  ejecutarSQL::consultar("SELECT de.NumPedido, pr.NombreProd, pr.Marca, pr.Modelo, pr.Precio, de.CantidadProductos FROM venta ve, detalle de, producto pr WHERE de.CodigoProd=pr.CodigoProd and ve.Estado='Pendiente' and ve.NumPedido=de.NumPedido and ve.Estado='Pendiente' order by NumPedido");                                        
                                                while($peU=mysql_fetch_array($pedidoU)){
                                                    echo '
                                                        <div id="update-pedido">
                                                            <tr>
                                                                <td>'.$peU['NumPedido'].'<input type="hidden" name="num-pedido" value="'.$peU['NumPedido'].'"></td>
                                                                <td>'.$peU['NombreProd'].'</td>
                                                                <td>'.$peU['Marca'].'</td>
                                                                <td>'.$peU['Modelo'].'</td>
                                                                <td>'.$peU['Precio'].'</td>
                                                                <td>'.$peU['CantidadProductos'].'</td>                                                            
                                                            </tr>
                                                        </div>
                                                        ';
                                                }
                                            } else if(!$_SESSION['nombreUser']==""){
                                                $nameClien=$_SESSION['nombreUser']; 
                                                $pedidoU=  ejecutarSQL::consultar("SELECT de.NumPedido, pr.NombreProd, pr.Marca, pr.Modelo, pr.Precio, de.CantidadProductos FROM venta ve, detalle de, producto pr WHERE de.CodigoProd=pr.CodigoProd and ve.Estado='Pendiente' and ve.NumPedido=de.NumPedido and ve.Usuario='".$nameClien."' and ve.Estado='Pendiente' order by NumPedido");                                        
                                                while($peU=mysql_fetch_array($pedidoU)){
                                                    echo '
                                                        <div id="update-pedido">
                                                            <tr>
                                                                <td>'.$peU['NumPedido'].'<input type="hidden" name="num-pedido" value="'.$peU['NumPedido'].'"></td>
                                                                <td>'.$peU['NombreProd'].'</td>
                                                                <td>'.$peU['Marca'].'</td>
                                                                <td>'.$peU['Modelo'].'</td>
                                                                <td>'.$peU['Precio'].'</td>
                                                                <td>'.$peU['CantidadProductos'].'</td>                                                            
                                                            </tr>
                                                        </div>
                                                        ';
                                                }
                                            }
                                          ?>
                                      </tbody>
                                  </table>
                              </div>                                    
                                    <div id="res-form-del-pedido" style="width: 100%; text-align: center; margin: 0;"></div>
                                </form>
                            </div>
                </div>
                <div class="col-xs-12 col-sm-6">
                    <div id="form-compra">
                        <form action="process/confirmcompra.php" method="post" role="form" class="FormCatElec" data-form="save">
                            <?php
                                if(!$_SESSION['nombreUser']=="" &&!$_SESSION['claveUser']==""){
                                    echo '
                                        <h2 class="text-center">¿Confirmar pedido?</h2>
                                        <p class="text-center">Para confirmar tu pedido presiona el botón confirmar</p>
                                        <br>
                                        <p class="text-center">Si quiere detallar la direccion de este pedido, indique los cambios aqui, caso contrario el pedido sera enviado a la direccion de registro.</p>
                                        <br>                                        
                                          <input type="hidden" name="clien-name" value="'.$_SESSION['nombreUser'].'">
                                          <input type="hidden" name="clien-pass" value="'.$_SESSION['claveUser'].'">
                                          <input type="text" class="form-control"  placeholder="Direccion" pattern=".{15,}" name="conf-dir" tittle="Minimo 15 caracteres">
                                          <br>
                                          <p class="text-center ">(*Minimo 15 caracteres)</p>
                                          <input type="hidden"  name="clien-number" value="log">
                                        <br>
                                        <p class="text-center"><button id="info" class="btn btn-success" type="submit">Confirmar</button></p>
                                    ';
                                }else if(!$_SESSION['nombreAdmin']==""){
                                    echo '
                                        <h2 class="text-center">¿Confirmar pedido local?</h2>
                                        <p class="text-center">Para confirmar tu pedido desde esta tienda presiona el botón confirmar</p>
                                        <br>                                       
                                          <input type="hidden" name="clien-name" value="Local">
                                          <input type="hidden" name="clien-pass" value="e10adc3949ba59abbe56e057f20f883e">
                                          <br>                                          
                                          <input type="hidden"  name="clien-number" value="log">
                                        <br>
                                        <p class="text-center"><button id="info" class="btn btn-success" type="submit">Confirmar</button></p>
                                    ';
                                }else{
                                    echo '
                                        <h3 class="text-center">¿Confirmar el pedido?</h3>
                                        <p>
                                            Para confirmar tu compra debes haber iniciar sesión o introducir tu nombre de usuario
                                            y contraseña con la cual te registraste en <span class="tittles-pages-logo">EL FARO DE ALEJANDRIA</span>.
                                        </p>
                                        <br>
                                      <div class="form-group">
                                        <div class="input-group">
                                          <div class="input-group-addon"><i class="fa fa-user"></i></div>
                                          <input class="form-control all-elements-tooltip" type="text" placeholder="Ingrese su nombre" required name="clien-name" data-toggle="tooltip" data-placement="top" title="Ingrese su nombre" pattern="[a-zA-Z]{1,9}" maxlength="9">
                                        </div>
                                      </div>
                                      <br>
                                      <div class="form-group">
                                        <div class="input-group">
                                          <div class="input-group-addon"><i class="fa fa-lock"></i></div>
                                          <input class="form-control all-elements-tooltip" type="password" placeholder="Introdusca su contraseña" required name="clien-pass" data-toggle="tooltip" data-placement="top" title="Introdusca su contraseña">
                                        </div>
                                      </div>
                                      <input type="hidden"  name="clien-number" value="notlog">
                                      <br>
                                      <p class="text-center"><button class="btn btn-success" type="submit">Confirmar</button></p>
                                    '; 
                                }
                            ?>
                            <div class="ResForm" style="width: 100%; text-align: center; margin: 0;"></div>
                        </form>
                    </div>
                    
                </div>
            </div>
        </div>
    </section>
    <?php include './inc/footer.php'; ?>
</body>
</html>