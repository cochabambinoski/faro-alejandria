<?php
include './library/jpgraph/src/jpgraph.php';
include './library/jpgraph/src/jpgraph_bar.php';

mysql_connect("localhost","root","");
mysql_select_db("store");

            $sql=mysql_query("SELECT * from detalle");             
            
            while($row=mysql_fetch_array($sql))
            {
                $datos[] = $row['CantidadProductos'];
                $labels[]= $row['CodigoProd'];
            }

                                $grafico = new Graph(500, 400, 'auto');
                                $grafico->SetScale("textint");
                                $grafico->title->Set("Ultimo Mes");
                                $grafico->xaxis->title->Set("Productos");
                                $grafico->xaxis->SetTickLabels($labels);
                                $grafico->yaxis->title->Set("Ventas");

                                $barplot1 =new BarPlot($datos);

                                $barplot1->SetFillGradient("#BE81F7","#E3CEF6", GRAD_HOR);

                                $barplot1->SetWidth(30);

                                $grafico->Add($barplot1);

                                $grafico->Stroke("IMG.PNG");
?>