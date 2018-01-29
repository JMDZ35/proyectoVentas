<?php

?>
<link href="<?= base_url('publico/css/dataTables.bootstrap.css')?>" rel="stylesheet">
 <script src="<?= base_url('publico/js/dataTables.bootstrap.js')?>"></script>
 <script src="<?= base_url('publico/js/jquery.dataTables.js')?>"></script>
 
  <!--PAGE CONTENT -->

   <div class="row">
   <div class="col-lg-12">
        <h1>
        <small>Plataforma de Operaciones WIAPP INTERNATIONAL</small></h1>
   </div>
   </div>
  
  <div class="row" id="actualiza">
  <div id="content">
           
    <div class="inner">
    
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3>           <small>    REGISTRAR PROYECTO</small></h3>
                        </div>
                        <div class="panel-body">
                        <div class="row">                               
                         <form action="<?= site_url('Proyecto/registrarProyecto')?>" method="post">

                              
                                     <div class="col-md-12">
                                      <div class="col-sm-6">                          
                                        <label class="col-xs-6 col-ms-3">
                                        Proyecto:
                                        <input type="text" name="nombre"  id='nombre'  placeholder="proyecto..."     value="" class="form-control"  required>
                                        <p id='result_idusuario' class="help-block"></p>
                                        </label>
                                  

                              
                             
                                        <label class="col-xs-6 col-ms-3">
                                        Cliente
                                        <select name="cliente"  class="form-control" required>
                                            <option ></option>
                                            <?php
                                            foreach ($bodyData->roles as $rolesTemp) {
                                            ?>
                                                <option name='opciones' value="<?=$rolesTemp->codigoEmpresa?>"><?=$rolesTemp->nombre?>-<?=$rolesTemp->nombreLocal?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        </label>
                                    <div class="col-xs-12 col-ms-3">
                                        <textarea class="form-control  col-md-6" name="descripcion" placeholder="Breve descripcion..." rows="2" id="comment"></textarea>
                                   
                                    
                                    </div>
                                    </div>                                        
                                      <div class="col-sm-6">                                                                           
                                        <label class="col-xs-6 col-ms-3">
                                        Periodo
                                        <select name="periodo"  class="form-control"  required>
                                            <option ></option>
                                          
                                                <option  value="1">ENERO</option>
                                                <option  value="2">FEBRERO</option>
                                                <option  value="3">MARZO</option>
                                                <option  value="4">ABRIL</option>
                                                <option  value="5">MAYO</option>
                                                <option  value="6">JUNIO</option>
                                                <option  value="7">JULIO</option>
                                                <option  value="8">AGOSTO</option>
                                                <option  value="9">SEPTIEMBRE</option>
                                                <option  value="10">OCTUBRE</option>
                                                <option  value="11">NOVIEMBRE</option>
                                                <option  value="12">DICIEMBRE</option>
                                                
                                         
                                        </select>
                                        </label>
                                    
                                        <label class="col-xs-6 col-ms-3">

                                       Tipo de Proyecto:
                                        <select name="tipoProyecto"  class="form-control"  required>
                                            <option ></option>
                                            <?php
                                            foreach ($bodyData->proyectos as $rolesTemp) {
                                            ?>
                                                <option name='opciones1' value="<?=$rolesTemp->id?>"><?=$rolesTemp->descripcion?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        </label>
                                      
                                    
                                    




                                         <div class="col-xs-6 col-ms-3">
                                        <label class="custom-file">
                                          <input type="file" id="file" class="custom-file-input">
                                          <span class="custom-file-control"></span>
                                        </label>
                                        </div>
                                    
                                        <div class="col-sm-12">
                                            <input  type ='submit' class="btn btn-primary btn-lg btn-block" id='btn_enviar' name='btn_enviar' 
                                               value="REGISTRAR PROYECTO" >

                                        </div>
                                         </div>
                                        </div>
                                   
                            </form>

                           </div>
                       </div>
                        </div>
                        
                    <div class="panel panel-default"  >
                        <div class="panel-heading" >
                            <h3>           <small>    REGISTRAR TAREA</small></h3>
                        </div>
                        <div class="panel-body">
                        <div class="row">                               
                        <form name="tarea_form" id="tarea_form" method="post">

                              <div class="col-md-12">

                                        
                                        <label class="col-xs-6 col-sm-3">
                                        Tarea:
                                        <input type="text" name="tarea"  id='tarea'  placeholder="Tarea..."     value="" class="form-control"  required>
                                        <p id='result_idusuario' class="help-block"></p>
                                        </label>
                                        <label class="col-xs-6 col-sm-3">
                                        Proyecto:
                                        <select name="proyectoTarea" id="proyectoTarea" class="form-control" required>
                                            <option ></option>
                                            <?php
                                            foreach ($bodyData->proyectoid as $rolesTemp) {
                                            ?>
                                                <option name='opciones' value="<?=$rolesTemp->id?>"><?=$rolesTemp->nombres?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        </label>                                        
 <div class="col-sm-6">  
                                        <label class="col-xs-6 col-sm-8">
                                        Tarea:
                                        <input type="text" name="descripcionTarea"  id='descripcionTarea'  placeholder="Descripcion..."     value="" class="form-control"  required>
                                        <p id='result_idusuario' class="help-block"></p>
                                        </label><br>
                                        <div class=" col-xs-6 col-sm-3">
                                            <input  type ='button' class="btn btn-danger" id='btn_enviarTarea' name='btn_enviarTarea' 
                                               value="REGISTRAR TAREA" >

                                        </div>
</div>                                    
                                    <div class="col-md-12">

                          <div class="panel-body" id="actualizable">
                               <div class="table-responsive">                                          
                                <table class="table table-striped table-bordered table-hover"  id="dataTables-tareas">
                                    <thead>
                                        <tr>
                                            <th>PROYECTO</th>
                                            <th>TAREA</th>
                                            <th>DESCRIPCION</th>
                                            <th>OPCIONES</th>                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                            
                                               
                                            <?php  
                                            foreach ($bodyData->valor as $value) { 
                                              
                                                ?>
                                                <tr>
                                                    
                                                    <td><?= $value->id?>                             </td>
                                                    <td>
                                                       <a data-id="<?= $value->id?>" name="cuentaEditorTrigger3" href="javascript:void(0)" >
                                                        <?= $value->nombreTarea?>   
                                                        </a>
                                                        
                                                    </td>                                                
                                                    <td><?= $value->descripcion?>                </td>
                                                    <td>
                                                    <a data-id="<?= $value->id?>" name="cuentaEditorTrigger3" href="javascript:void(0)" class="fa fa-pencil-square-o"></a>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <a data-id="<?= $value->id?>" name="cuentaEditorTrigger4" href="javascript:void()" class="fa fa-minus-square"></a>

                                                    </td>  

 
                                           
                                                </tr> 
                                            <?php 
                                          
                                            }
                                            ?>
                                           
                                    </tbody>
                                </table>


                               </div>
                            </div>


                                    </div>
                              </div>
                        
                                   
                        
                        </form>


                           </div>
                       </div>
                        </div>                        
                              <div class="panel panel-default">     
                              <div class="panel-heading">
                           <h3> <small>    HISTORIAL DE PROYECTOS</small></h3>
                              </div>
                          <div class="panel-body" >
                               <div class="table-responsive">                                          
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>CODIGO</th>
                                            <th>PROYECTO</th>
                                            <th>CLIENTE</th>
                                            <th>CONTACTO</th>
                                            <th>PERIODO</th>
                                            <th>STATUS</th>
                                            <th>NOTAS</th>
                                            <th>OPCIONES</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                            
                                               
                                            <?php  
                                            foreach ($bodyData->valores as $value) { 
                                              
                                                ?>
                                                <tr>
                                                    
                                                    <td><?= $value->id?>                             </td>
                                                    <td>
                                                       <a data-id="<?= $value->id?>" name="cuentaEditorTrigger2" href="javascript:void(0)" >
                                                        <?= $value->nombres?>   
                                                        </a>
                                                        
                                                    </td>                                                
                                                    <td><?= $value->nombreLocal?>                </td>
                                                    <td><?= $value->contacto?>                  </td> 
                                                    <td><?= $value->periodo?>                  </td> 
                                                    <td><?= $value->descripcion?>                  </td> 
                                                    <td><?= $value->contacto?>                  </td> 
                                                    <td>
                                                    <a data-id="<?= $value->id?>" name="cuentaEditorTrigger" href="javascript:void(0)" class="fa fa-pencil-square-o"></a>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <a data-id="<?= $value->id?>" name="cuentaEditorTrigger1" href="javascript:void()" class="fa fa-minus-square"></a>

                                                    </td>  
                                           
                                                </tr> 
                                            <?php 
                                          
                                            }
                                            ?>
                                           
                                    </tbody>
                                </table>


                               </div>
                            </div>
                                   
                             
                        
                                       

    
                    </div>
                
    
        
    </div>
  </div>
  </div>
  </div>
  </div>

<!--  MODAL DE LISTA DE REGISTROS -->
<div class="modal   fade" id="permisosModal" tabindex="-1" role="dialog" aria-labelledby="permisosModalLabel" >
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="permisosModalLabel">Gestion de Permisos</h4>
            </div> 
            <div class="modal-body"  style="overflow-y: auto;">
                <center><img src="<?= base_url('publico/media/ajax-loader2.gif')?>" width="80" height="80" ></center>'
              <!-- ACA es la Zona de Contenido... SI solo tuviera algo que colocar T.T -->
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-sm-9 nombre" style="text-align: left;"> 
                    </div>
                    <div class="col-sm-3">
                        <form > 
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--  MODAL DE LISTA DE Areas -->
<div class="modal   fade" id="cuentaEditorModal" tabindex="-1" role="dialog" aria-labelledby="cuentaEditorModalLabel" >
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="ubicacionesModalLabel">EDICION DEL PROYECTO </h4>
            </div> 
            <div class="modal-body"  style=" ">
                <center><img src="<?= base_url('publico/media/ajax-loader2.gif')?>" width="80" height="80" ></center>'
              <!-- ACA es la Zona de Contenido... SI solo tuviera algo que colocar T.T -->
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-sm-9 nombre" style="text-align: left;"> 
                    </div>
                    <div class="col-sm-3">
                        <form > 
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal   fade" id="cuentaEditorModal1" tabindex="-1" role="dialog" aria-labelledby="cuentaEditorModalLabel" >
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="ubicacionesModalLabel">EDICION DEL PROYECTO </h4>
            </div> 
            <div class="modal-body"  style=" ">
                <center><img src="<?= base_url('publico/media/ajax-loader2.gif')?>" width="80" height="80" ></center>'
              <!-- ACA es la Zona de Contenido... SI solo tuviera algo que colocar T.T -->
            </div>

        </div>
    </div>
</div>
<div class="modal   fade" id="cuentaEditorModal2" tabindex="-1" role="dialog" aria-labelledby="cuentaEditorModalLabel" >
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="ubicacionesModalLabel">DOCUMENTOS OBLIGATORIOS* </h4>
            </div> 
            <div class="modal-body"  style=" ">
                <center><img src="<?= base_url('publico/media/ajax-loader2.gif')?>" width="80" height="80" ></center>'
              <!-- ACA es la Zona de Contenido... SI solo tuviera algo que colocar T.T -->
            </div>

        </div>
    </div>
</div>
<div class="modal   fade" id="cuentaEditorModal3" tabindex="-1" role="dialog" aria-labelledby="cuentaEditorModalLabel" >
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="ubicacionesModalLabel">EDICION DE TAREAS* </h4>
            </div> 
            <div class="modal-body"  style=" ">
                <center><img src="<?= base_url('publico/media/ajax-loader2.gif')?>" width="80" height="80" ></center>'
              <!-- ACA es la Zona de Contenido... SI solo tuviera algo que colocar T.T -->
            </div>

        </div>
    </div>
</div>
<div class="modal   fade" id="cuentaEditorModal4" tabindex="-1" role="dialog" aria-labelledby="cuentaEditorModalLabel" >
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="ubicacionesModalLabel">DESACTIVAR TAREA </h4>
            </div> 
            <div class="modal-body"  style=" ">
                <center><img src="<?= base_url('publico/media/ajax-loader2.gif')?>" width="80" height="80" ></center>'
              <!-- ACA es la Zona de Contenido... SI solo tuviera algo que colocar T.T -->
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-sm-9 nombre" style="text-align: left;"> 
                    </div>
                    <div class="col-sm-3">
                        <form > 
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {    
    $('[name="cuentaEditorTrigger4"]').click(function($element){
        var id = $(this).attr("data-id"); 
        $( "#cuentaEditorModal4" ).modal("show"); 
        enviarData(id);
    }); 
    function enviarData (id){
        $.ajax({
            url: "<?= site_url('Proyecto/mensajeBloqueoTarea/') ?>"+id,
            method: "GET",
           // data: { id : id },
            dataType: "html"
        }).done(function( msg ) { //alert("bien");
            var height = $(window).height() * 0.60 ;
            var width = $(window).width()  * 0.70 ;
            var modal = $( "#cuentaEditorModal4" );
            modal.find('.modal-body') .html( msg );
        }).fail(function( jqXHR, textStatus ) {
            var msj ="Error de Conexion";
            if(jqXHR.status===401){
                msj ="Acceso Denegado";
            }
            var modal = $( "#ubicacionesModal" );
        

    modal.find('.modal-body') .html( "<p> "+msj+" </p>" );
        });        
    }
});    
</script>
<script>
$(document).ready(function() {    
    $('[name="cuentaEditorTrigger3"]').click(function($element){
        var id = $(this).attr("data-id"); 
        $( "#cuentaEditorModal3" ).modal("show"); 
        enviarData(id);
    }); 
    function enviarData (id){
        $.ajax({
            url: "<?= site_url('Proyecto/editarTarea/') ?>"+id,
            method: "GET",
           // data: { id : id },
            dataType: "html"
        }).done(function( msg ) { //alert("bien");
            var height = $(window).height() * 0.60 ;
            var width = $(window).width()  * 0.70 ;
            var modal = $( "#cuentaEditorModal3" );
            modal.find('.modal-body') .html( msg );
        }).fail(function( jqXHR, textStatus ) {
            var msj ="Error de Conexion";
            if(jqXHR.status===401){
                msj ="Acceso Denegado";
            }
            var modal = $( "#ubicacionesModal" );
        

    modal.find('.modal-body') .html( "<p> "+msj+" </p>" );
        });        
    }
});    
</script>
<script>
$(document).ready(function() {    
    $('[name="cuentaEditorTrigger2"]').click(function($element){
        var id = $(this).attr("data-id"); 
        $( "#cuentaEditorModal2" ).modal("show"); 
        enviarData(id);
    }); 
    function enviarData (id){
        $.ajax({
            url: "<?= site_url('Proyecto/agregardocumentos/') ?>"+id,
            method: "GET",
           // data: { id : id },
            dataType: "html"
        }).done(function( msg ) { //alert("bien");
            var height = $(window).height() * 0.60 ;
            var width = $(window).width()  * 0.70 ;
            var modal = $( "#cuentaEditorModal2" );
            modal.find('.modal-body') .html( msg );
        }).fail(function( jqXHR, textStatus ) {
            var msj ="Error de Conexion";
            if(jqXHR.status===401){
                msj ="Acceso Denegado";
            }
            var modal = $( "#ubicacionesModal" );
        

    modal.find('.modal-body') .html( "<p> "+msj+" </p>" );
        });        
    }
});    
</script>

<script>
$(document).ready(function() {    
    $('[name="cuentaEditorTrigger1"]').click(function($element){
        var id = $(this).attr("data-id"); 
        $( "#cuentaEditorModal1" ).modal("show"); 
        enviarData(id);
    }); 
    function enviarData (id){
        $.ajax({
            url: "<?= site_url('Proyecto/mensajeBloqueo/') ?>"+id,
            method: "GET",
           // data: { id : id },
            dataType: "html"
        }).done(function( msg ) { //alert("bien");
            var height = $(window).height() * 0.75 ;
            var width = $(window).width()  * 0.90 ;
            var modal = $( "#cuentaEditorModal1" );
            modal.find('.modal-body') .html( msg );
        }).fail(function( jqXHR, textStatus ) {
            var msj ="Error de Conexion";
            if(jqXHR.status===401){
                msj ="Acceso Denegado";
            }
            var modal = $( "#ubicacionesModal" );
        

    modal.find('.modal-body') .html( "<p> "+msj+" </p>" );
        });        
    }
});    
</script>


   <script>         
$(document).ready(function() {    
    $('[name="cuentaEditorTrigger"]').click(function($element){
        var id = $(this).attr("data-id"); 
        $( "#cuentaEditorModal" ).modal("show"); 
        enviarData(id);
    }); 
    function enviarData (id){
        $.ajax({
            url: "<?= site_url('Proyecto/datosPerfil/') ?>"+id,
            method: "GET",
           // data: { id : id },
            dataType: "html"
        }).done(function( msg ) { //alert("bien");
            var height = $(window).height() * 0.75 ;
            var width = $(window).width()  * 0.90 ;
            var modal = $( "#cuentaEditorModal" );
            modal.find('.modal-body') .html( msg );
        }).fail(function( jqXHR, textStatus ) {
            var msj ="Error de Conexion";
            if(jqXHR.status===401){
                msj ="Acceso Denegado";
            }
            var modal = $( "#ubicacionesModal" );
        

    modal.find('.modal-body') .html( "<p> "+msj+" </p>" );
        });        
    }
});    








 
var f=new Date();
var ano = f.getFullYear();
var mes = f.getMonth();
var dia = f.getDate();
var estiloDia;
var meses = new Array ("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
var diasSemana = new Array("Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado");
var diasMes = new Array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
var diaMaximo = diasMes[mes];
if (mes == 1 && (((ano % 4 == 0) && (ano % 100 != 0)) || (ano % 400 == 0)))
   diaMaximo = 29;

        
        
/*By George Chiang (WA's JavaScript tutorial)

Credit must stay intact for use*/
function show(){
var Digital=new Date();
var hours=Digital.getHours();
var minutes=Digital.getMinutes();
var seconds=Digital.getSeconds();
var dn="AM" ;
if (hours>12){
dn="PM";
hours=hours-12;
}
if (hours==0){
hours=12;
}
if (minutes<=9){
minutes="0"+minutes;}
if (seconds<=9){
seconds="0"+seconds;}
    $('#hora').val(hours+":"+minutes+":"+seconds+" "+dn);// IMPRIMO LA HORA
setTimeout("show()",1000);
}
show();
        
 
			

     
    fechaImprimible =diasSemana[f.getDay()] + ", " + f.getDate() + " de " + meses[f.getMonth()] + " del " + f.getFullYear() ;
   
        
    $("#fecha").val(fechaImprimible); //IMPRIMO LA FECHA
   	 

   //MODAL


    </script>
    <script>

    
        $("#btn_enviarTarea").click(function(){
            var tareaid = $("#proyectoTarea").val();

            
            $.ajax({
                url : "<?= site_url('Proyecto/registrarTarea') ?>",                
                type: "POST",
                data: $("#tarea_form").serialize(),
            }).success(function(){
    javascript:location.reload();                           
  //    $("#actualizable").load("<?= site_url('Proyecto/mostrarTareaProyecto/') ?>+tareaid"); 

                        });                                                                                                                                                                                          
                      
        });;
            

        

    </script>
     <script>
         $(document).ready(function () {
             $('#dataTables-example').dataTable();
             $('#dataTables-tareas').dataTable();

         });
         
    </script>




<div class="row" style="    padding: 40px;"></div>