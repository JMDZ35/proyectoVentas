<?php

    $modulos = $this->session->webCasSession->modulos; // al final solo quedaran los modulos no agrupados
    $modulosGrupos = $this->session->webCasSession->modulosGrupos;
    $usuario = $this->session->webCasSession->usuario;// los grupos sin registros son borrados
    
    /*
     *   incluye una key q pertenece a $modulosGrupos[id] donde se almacenan multiples modulos  : 
     * $modulosGrupos_agrupados [$modulosGrupos[id] ]{ array( $modulos ), n..    }
     * lista final que se mostrara en el menu de modulos agrupados 
     */
    $modulosGrupos_agrupados = array();

    
    
    class organizarPila{
        
        public $modulos,$modulosGrupos,$modulosGrupos_agrupados;
        private $modulosGrupos_agrupados_Borrar=array();
    
        public function __construct($modulos,$modulosGrupos,$usuario) {
            $this->modulos = $modulos;
            $this->modulosGrupos = $modulosGrupos;
            $this->usuario=$usuario;            
            $this->agrupar();
            $this->iniAnidado();
        }
        
        private function agrupar (){
            // agrupar los modulos y depurador de grupos no-utilizados
            $valor=1;
     if($this->usuario->ROLES!=1){
            foreach ($this->modulosGrupos as $modulosGrupoKey => $modulosGrupo) {
                $verficadorDeUso = 0;
                foreach ($this->modulos as   $moduloKey => $modulo) {
                    if(!empty($modulo->WebModulosGrupos_id ) and $modulo->WebModulosGrupos_id == $modulosGrupo->id and $modulo->permiso==1 ){
                        $this->modulosGrupos_agrupados [$modulosGrupo->id][] =  $modulo; 
                        unset($this->modulos[$moduloKey]);
                        $verficadorDeUso++;
                    }else{
                     //   break;
                    }
                }
                if ($verficadorDeUso == 0){
                    unset($this->modulosGrupos[$modulosGrupoKey]);
                } 
            }
     }else{
         
            foreach ($this->modulosGrupos as $modulosGrupoKey => $modulosGrupo) {
                $verficadorDeUso = 0;
                foreach ($this->modulos as   $moduloKey => $modulo) {
                    if(!empty($modulo->WebModulosGrupos_id ) and $modulo->WebModulosGrupos_id == $modulosGrupo->id){
                        $this->modulosGrupos_agrupados [$modulosGrupo->id][] =  $modulo; 
                        unset($this->modulos[$moduloKey]);
                        $verficadorDeUso++;
                    }
                }
                if ($verficadorDeUso == 0){
                    unset($this->modulosGrupos[$modulosGrupoKey]);
                } 
            }         
         
     }
            /// FIN de agrupar modulos 
        }
        
        private function borrar_replicados(){ 
            $vectorTemporal = array(); 
            
            foreach ($this->modulosGrupos_agrupados_Borrar as $key => $value) {
                $value= (object)$value;
                //var_dump($value);
                 foreach ($this->modulosGrupos_agrupados[$value->grupo_id] as $Bkey => $Bvalue) {
                     if($value->modulo_id  == $Bvalue->id  ) {
                        unset( $this->modulosGrupos_agrupados[$value->grupo_id ][$Bkey] ) ; 
                    }
                }  
            }
        }
        
        private function anidar( $array_por_incrustar,  $arra_multidimensional, $grupo_id) {
            if(empty(  $array_por_incrustar->self_WebModulos_id_parent )) {   
                return $arra_multidimensional; 
            } 
            //var_dump($arra_multidimensional);
            foreach ($arra_multidimensional  as $lista_key => $lista_array) { 
                if(  $array_por_incrustar->self_WebModulos_id_parent  == $lista_array->id   ){
                    //var_dump($array_por_incrustar->self_WebModulos_id_parent  ."==". $lista_array->id .'--------',$arra_multidimensional[$lista_key] );
                    if (!isset($lista_array->hijos)){
                        $arra_multidimensional[$lista_key]  = (object) array_merge((array)$lista_array, array('hijos'=>array())) ; 
                    }
                    $arra_multidimensional[$lista_key]->hijos[] = $array_por_incrustar;  
                    $this->modulosGrupos_agrupados_Borrar[] = (object) array('modulo_id'=>$array_por_incrustar->id,'grupo_id'=>$grupo_id) ; 
                }elseif(isset($lista_array->hijos) ){
                    $arra_multidimensional[$lista_key]->hijos = $this->anidar($array_por_incrustar,  $arra_multidimensional[$lista_key]->hijos ,$grupo_id);
                }
            } 
            return $arra_multidimensional;
        }
         
        private function iniAnidado() { 
            foreach ($this->modulosGrupos  as   $mV) { 
                $modulosGrupos_agrupados_1grupo_temporal = $this->modulosGrupos_agrupados[$mV->id] ;
                foreach ($modulosGrupos_agrupados_1grupo_temporal as $moduloDentroDelAgrupadoKEY =>  $moduloDentroDelAgrupado) { 
                    if(!empty($moduloDentroDelAgrupado->self_WebModulos_id_parent )){
                        $this->modulosGrupos_agrupados[$mV->id] = $this->anidar ($moduloDentroDelAgrupado , $this->modulosGrupos_agrupados[$mV->id] , $mV->id  );
                    }
                }  
            }
            $this->borrar_replicados( );
        }
        
        public function graficarModulo($modulo) {          
            //var_dump($modulo);exit();
            //echo'<br>';
            if($this->usuario->ROLES==1){
            if($modulo->modus!=2){  
            if(empty($modulo->isVisible ))return;/// no graficar los q deben estar ocultos (AJAX u otros)
            $url =  ((strlen($modulo->uri )>1) and $modulo->uri!=NULL )?'href="'.site_url($modulo->uri ).'"' :'' ;
            if(  !isset($modulo->hijos )){
                echo ' <li><a '.$url.' ><i class=" '.$modulo->html_clases .'"></i>'.$modulo->titulo .' <span class=" "></span></a> </li>';
            }else {
            if($modulo->modus!=3){
                echo '<li><a  '.$url.' ><i class="'.$modulo->html_clases .'"></i>'.$modulo->titulo .'<span class="fa fa-chevron-down"></span></a>   '.
                        '<ul class="nav child_menu">'; 
                foreach ($modulo->hijos  as $hijo) {
                    $hijo->html_clases = '';
                    $this->graficarModulo($hijo);   
                }    
                echo    '</ul>'. '</li> ';
            }
            }
                }
            
                }
            else{
                    
            if(empty($modulo->isVisible ))return;/// no graficar los q deben estar ocultos (AJAX u otros)
            $url =  ((strlen($modulo->uri )>1) and $modulo->uri!=NULL )?'href="'.site_url($modulo->uri ).'"' :'' ;
            if(  !isset($modulo->hijos ) ){
                echo ' <li><a '.$url.' ><i class=" '.$modulo->html_clases .'"></i>'.$modulo->titulo .' <span class=" "></span></a> </li>';
            }else {
                echo '<li><a  '.$url.' ><i class="'.$modulo->html_clases .'"></i>'.$modulo->titulo .'<span class="fa fa-chevron-down"></span></a>   '.
                        '<ul class="nav child_menu">'; 
                foreach ($modulo->hijos  as $hijo) {
                    $hijo->html_clases = '';
                    $this->graficarModulo($hijo);   
                }    
                echo    '</ul>'. '</li> ';
                }       
                
                
            }
        }
       
         
        public function graficarGrupos() { 
          //  var_dump($this->modulosGrupos_agrupados[3 ]); exit();
            foreach ($this->modulosGrupos as   $modulosGrupo) { 
                
                echo '<div "> '.
                    //cambio para que no salga el nombre del cargo    '<h3>'.$modulosGrupo->titulo.'</h3>'.
                            '<ul class="nav side-menu">';
                
                foreach ($this->modulosGrupos_agrupados[$modulosGrupo->id ] as $modulosGrupos_agrupados) {
                    $this->graficarModulo($modulosGrupos_agrupados); 
                    //if($modulosGrupo->id==3) var_dump($modulosGrupo->id ,$modulosGrupos_agrupados); // exit();
                } 
               echo     ' </ul>'.
                    '</div>'; 
            } 
        } 
        
        public function graficarGeneral() { 
            $tieneModulosVisibles = false;
            $valor=1;
            if($this->usuario->ROLES!=1){
            foreach ($this->modulos as $modulo) { 
                if(!empty($modulo->isVisible) and $modulo->permiso==1){
                    $tieneModulosVisibles = true ;
                    break;
                }else{
                    $tieneModulosVisibles = false ;
                    break;                    
                }
            }
        }else{
            foreach ($this->modulos as $modulo) { 
                if(!empty($modulo->isVisible) ){
                    $tieneModulosVisibles = true ;
                    break;
                }
            }            
        }
            if(!$tieneModulosVisibles)return;
            echo '<div class="menu_section"> '.
                    '<h3>General</h3>'.
                        '<ul class="nav side-menu">'.count($this->modulos); 
            foreach ($this->modulos as $modulo) { 
                $this->graficarModulo($modulo); 
            } 
            echo     '</ul>'.
                '</div>'; 
        } 
        
        public function graficarInicio() {  
            echo '<div class="menu_section"> '.
                    ' '.
                        '<ul class="nav side-menu">'; 
            foreach ($this->modulos as $key => $modulo) {
                if(empty($modulo->esPanelInicial))
                    continue;
                $this->graficarModulo($modulo); 
                unset(  $this->modulos[$key] );
            } 
            echo     '</ul>'.
                '</div>'; 
        } 
         
    } 
      
    $class = new organizarPila($modulos,$modulosGrupos,$usuario); 
   // var_dump($class->modulos);exit();
?>


<div class="col-md-3 left_col">
    <div class="left_col scroll-view">
      <div class="navbar nav_title" style="border: 0;">
        <a href="panel" class="site_title">
            <!--<i class="fa fa-paw"></i> <span>Gentelella Alela!</span> -->
         <!--   <h3 class="tituloWebsite quitar_margen"> -->
         <img src="<?= base_url('publico/media/logo.png')?>" width="35" height="25"  style="margin-right: 5px;">SGV
            <span class="destacarTexto"></span><!--</h3>-->
        </a>
      </div>

      <div class="clearfix"></div>

      <!-- menu profile quick info -->
      <div class="profile clearfix">
      
        <div class="profile_info">
            <span>Bienvenido, <strong><?=$this->session->webCasSession->usuario->USUARIO?></strong></span>
        </div>
      </div>
      <!-- /menu profile quick info -->

   
      
      <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">  
            <?php
                $class->graficarInicio();  
                $class->graficarGrupos();  
                $class->graficarGeneral();
            ?>  
      </div>
      <!-- /sidebar menu -->

      <!-- /menu footer buttons -->
      <div class="sidebar-footer hidden-small">
          
        <a >
          <span class="glyphicon " ></span>
        </a>
        <a    >
          <span class="glyphicon " ></span>
        </a>
        <a  >
          <span class="glyphicon  " ></span>
        </a>
          <a data-toggle="tooltip" href="<?= site_url('login/logout')?>"  data-placement="top" title="Logout">
          <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
        </a>
      </div>
      <!-- /menu footer buttons -->
    </div>
  </div>