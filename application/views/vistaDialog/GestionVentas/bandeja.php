<?php
?>
                        

                          <div class="panel-body">
                               <div class="table-responsive">                                          
                                <table class="table table-striped table-bordered dt-responsive nowrap  " cellspacing="0" width="100%" id="dataTables-usuario">
                                    <thead class="bg-success" >
                                        <tr>
                                     <th style="border: hidden;color: #3b752e;"><center>NOMBRES</center> </th>
                                     <th style="border: hidden;color: #3b752e;"><center>USUARIO</center> </th>                                            
                                     <th style="border: hidden;color: #3b752e;"><center>TELEFONO</center></th>
                                     <th style="border: hidden;color: #3b752e;"><center>CORREO</center>  </th>
                                     <th style="border: hidden;color: #3b752e;"><center> CARGO</center>  </th>
                                     <th style="border: hidden;color: #3b752e;"><center>OPCIONES</center></th>                                                                                        
                                        </tr>
                                    </thead>
                                   
                                    <tbody>       
                                        
                                            <?php
                                                
    
                                            foreach ($bodyData->cuentas as $cuentasTemp) {                                                
                                            ?>
                                                  <tr>  
                                                      <td>        <?=$cuentasTemp->PERSONA  ?></td>
                                                      <td><CENTER><?=$cuentasTemp->USUARIO   ?></CENTER></td>
                                                      <td><CENTER><?=$cuentasTemp->TELEFONO  ?></CENTER></td>
                                                      <td><CENTER><?=$cuentasTemp->CORREO    ?></CENTER></td>
                                                      <td><CENTER><?=$cuentasTemp->CARGO     ?></CENTER></td>
                                                      <td>
                                                    <label style="margin-right: 7px;">
                                                        <i class="fa fa-edit"></i>
                                                        <a href="javascript:void(0)" data-id="<?=$cuentasTemp->CODIGO?>" class="Editar">Cuenta</a> 
                                                    </label>


                                                    <label style=" ">
                                                        <i class="fa fa-lock"></i>
                                                        <a href="#"></a>
                                                    </label>
                                                      </td>
                                                  </tr>
                                            <?php
                                                }
                                            ?>                                        
                                          
                                    </tbody>
                                </table>
                               </div>
                            </div>                                                                                                                                  
              

<script type="text/javascript">
$("#dataTables-usuario").dataTable();
</script>