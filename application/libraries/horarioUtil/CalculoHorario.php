<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'libraries'.DIRECTORY_SEPARATOR.'horarioUtil'.DIRECTORY_SEPARATOR.'Retorno.php';
             

class CalculoHorario {
    
    /*/*
     *  Array con los datos Finales 
     * donde $retorno = array( 'Date'=> new Retorno(), ... );
     */
    private  $retorno =  array() ;  
    
    
    // array con todos los horarios
    public  $horariosTodos ;
    
    // array con todas las excepciones
    public $excepcionesTodos ;
    
    /*
     * este atributo segun el rango de fecha que se requiera, crea un array por dia que almacena
     * la vecha a  la que corresponde (COMO LLAVE del array) y como valor el objeto con toda la informacion
     * Pudiendo ser el objeto  una referencia a el HORARIO o EXCEPCION
     * 
     * cada KEY debe tener de valor la fecha del dia a procesar array('20170201'=>objetoData)
     */
    private $horarioExcepcionPorFecha = array();
    
    /* 
     * almacena la informacion de la tabla atnd_userdailyschedule_tbl (tnduds_exception_id,atnduds_s1_timezoneid,atnduds_date)
     * contiene un array con tres campos  array(  ID_Excepcion,ID_horario , Fecha_correspondiente) 
     * 
     * A partir de este atributo se busca cual es la excepcion q le corresponde por dia o el horario
     */
    private $horarioExcepcionDiaria_IdDate = array();
    
    // horario fijo de cada semana lunes a domingo  array( 1 => DATOS_LUNES )
    private $horariosSemanalFijo = array() ;  
    
    // grupo horarios fijo 
    private $atndGroup ;  
     
    // array con Fecha como KEY  ej array('20170201'=>objetoData)
    private $dataProcesadaPorDia ;  
    
    // lista de marcas validas desde y hasta por id de usuario
    private $marcas ;    
    
    //instancia controller
    private $ci ;
    
    // infor de busqueda
    private $fechaDesde,$fechaHasta,$usuarioId;
    
    public function GetData() {
        return $this->retorno;
    }
    
    function __construct ( ) {
        $this->ci =& get_instance(); 
        $this->ci->load->model("Registros_model",'',TRUE);
        $this->ci->load->model("Horarios_model",'',TRUE);
        $this->ci->load->model("Excepciones_model",'',TRUE);
        $this->ci->load->model("DataProcesada_model",'',TRUE);
        
    }
     
    public function IndividualGenerarMostrar($desde,$hasta,$usuarioId,$ignorarHorario=false,$ignorarExcepciones=false) {
        try{
            $this->fechaDesde = $desde;
            $this->fechaHasta = $hasta;
            $this->usuarioId = $usuarioId; 

            $this->SetData($ignorarHorario, $ignorarExcepciones);// carga todo lo necesario de la Base de datos 

            $fecha = new DateTime($this->fechaDesde);
            $segundos=strtotime($this->fechaHasta) - strtotime($this->fechaDesde);
            $diferencia_dias=intval($segundos/60/60/24); 
            
            for($i=0;$i<=$diferencia_dias;$i++){
                $this->CrearDataDia($fecha->format('Ymd'));
                $fecha->add(new DateInterval('P1D')) ;
                if($i%2){
                 //   usleep(50000);//50 milisegundos cada dos ciclos
                }
            } 
            
        } catch (Exception $e){ 
            return $this->retorno;
        } 
        return $this->retorno; 
    }
    
    public function MostrarDataDia($desde,$hasta,$usuarioId) {
        try{            
            $this->fechaDesde = $desde;
            $this->fechaHasta = $hasta;
            $this->usuarioId = $usuarioId; 

            $this->SetData();// carga todo lo necesario de la Base de datos 

            $fecha = new DateTime($this->fechaDesde);
            $segundos=strtotime($this->fechaHasta) - strtotime($this->fechaDesde);
            $diferencia_dias=intval($segundos/60/60/24); 
            
            for($i=0;$i<=$diferencia_dias;$i++){
                $this->MostrarDataAcoplar($fecha->format('Ymd'));
                $fecha->add(new DateInterval('P1D')) ;
            }     
            
        } catch (Exception $e){
            return $this->retorno;
        }
        return $this->retorno;
    }
    
    
    public function IndividualGuardarListar($desde,$hasta,$usuarioId,$siExisteRemplaza = false,$DbSobreTemp = true) {
        try{
            $this->fechaDesde = $desde;
            $this->fechaHasta = $hasta;
            $this->usuarioId = $usuarioId; 

            $this->SetData();// carga todo lo necesario de la Base de datos 

            $fecha = new DateTime($this->fechaDesde);
            $segundos=strtotime($this->fechaHasta) - strtotime($this->fechaDesde);
            $diferencia_dias=intval($segundos/60/60/24); 
            
            for($i=0;$i<=$diferencia_dias;$i++){
                $this->CrearDataDia($fecha->format('Ymd'));
                $fecha->add(new DateInterval('P1D')) ;
            }            
            
            foreach ($this->retorno as $fechaDate => $retornoTemp) {
                if( !empty($this->dataProcesadaPorDia[$fechaDate])){ 
                    if( ((int)$this->dataProcesadaPorDia[$fechaDate]->atndd_modified) > 0 ){
                //        echo " Shift1 Fue modificado y solo se actualiza data a menos q $siExisteRemplaza sea true ";
                        if($siExisteRemplaza){
                            $this->ci->DataProcesada_model->UpdateProcessedDate($this->limpiarElementosAdicionales($retornoTemp),2);
                        }
                        $this->ci->DataProcesada_model->UpdateProcessedDate($this->limpiarElementosAdicionales($retornoTemp),1); //solo data
                    }else{
                //        echo " Shift1 NO estaba modificado "; 
                        $this->ci->DataProcesada_model->UpdateProcessedDate($this->limpiarElementosAdicionales($retornoTemp),1);
                        $this->ci->DataProcesada_model->UpdateProcessedDate($this->limpiarElementosAdicionales($retornoTemp),2); //actualizar ambos 
                    } 
                    
                    if($DbSobreTemp){ 
                        /*
                         * Solo se puede editar los horarios/excepciones y las entradas y salidas por ello
                         * solo se reemplazaran esos datos de la variable temporal por la de shift1
                        */
                        // se resuelve mejor con merge mero no muestra la data actualizada al cambiar horario
                        $this->retorno[$fechaDate]->atndd_atndtz_id = $this->dataProcesadaPorDia[$fechaDate]->atndd_atndtz_id;
                        $this->retorno[$fechaDate]->atndd_atndex_id = $this->dataProcesadaPorDia[$fechaDate]->atndd_atndex_id;
                        $this->retorno[$fechaDate]->atndd_atnd_in   = $this->dataProcesadaPorDia[$fechaDate]->atndd_atnd_in;
                        $this->retorno[$fechaDate]->atndd_atnd_out  = $this->dataProcesadaPorDia[$fechaDate]->atndd_atnd_out;
                        $this->retorno[$fechaDate]->nombreEditor    = $this->dataProcesadaPorDia[$fechaDate]->nombreEditor;
                        $this->retorno[$fechaDate]->atndd_modified    = $this->dataProcesadaPorDia[$fechaDate]->atndd_modified;
                         
                      //  $this->retorno[$fechaDate] = (object) array_merge( (array) $this->retorno[$fechaDate], (array) $this->dataProcesadaPorDia[$fechaDate]);
                          
                    }
                } else{ 
                //      echo " No existen , Registrar  ";
                    $this->RegistrarModel($retornoTemp);
                //    echo " No existen , Registrar  ";
                }
            }
        } catch (Exception $e){
        //    echo "Error ";
            return $this->retorno;
        }
    //  echo "Normal ";
        return $this->retorno;
    } 
    
        
    
    
    
    private function MostrarDataAcoplar($fechaDate) {
        // DATOS ESTANDAR
        if(!checkdate(substr($fechaDate, 4,2), substr($fechaDate, 6,2), substr($fechaDate, 0,4)))return;
        $usaHorarioFijo = false; // flag de marca si utiliza horario fijo o diario
        $timezoneOExcepcion ;// horario o excepcion correspondiente para este dia
        $marcas_modulosDelimitados = array(); //describir
        if ( !empty($this->horarioExcepcionPorFecha[$fechaDate]) ){
            $timezoneOExcepcion = $this->horarioExcepcionPorFecha[$fechaDate] ;
        } else {
            $timezoneOExcepcion = (empty($this->horariosSemanalFijo[date('N', strtotime($fechaDate) )]) )? null : $this->horariosSemanalFijo[date('N', strtotime($fechaDate) )]  ; 
            $usaHorarioFijo = true; 
        }
        
        /// ASIGNACION BASICA
        $datosRetorno = new Retorno(); // objeto que contiene la fila con datos a insertar o cambiar
        $datosRetorno->atndd_id = $this->usuarioId;
        $datosRetorno->atndd_date = $fechaDate;
        // FIN ASIGNACION BASICA
        
        if(!empty($timezoneOExcepcion->atndtz_bt_period)){
            $bt = $this->obtenerModuloBt($fechaDate, $timezoneOExcepcion) ;// dia laboral 
            $datosRetorno->justificador  = $this->agruparMarcas($bt[0],$bt[1])  ; // lista de marcas para dar detalles 
        }
        $datosRetorno->horarioExcepcion = $timezoneOExcepcion; //Datos de la excepcion
        
        if(empty($this->dataProcesadaPorDia[$fechaDate])){return;}
        $this->retorno[$fechaDate] = (object) array_merge( (array) $datosRetorno , (array) $this->dataProcesadaPorDia[$fechaDate]);
    }
    
    /*
     * desde esta seccion en adelante se procesan los datos     * 
     */
    
    /*
     * Funccion Principal donde se Calcula la Data procesada por dia
     */
    private function CrearDataDia($fechaDate) {
        try{
            // DATOS ESTANDAR
            if(!checkdate(substr($fechaDate, 4,2), substr($fechaDate, 6,2), substr($fechaDate, 0,4)))return;
            $usaHorarioFijo = false; // flag de marca si utiliza horario fijo o diario
            $timezoneOExcepcion ;// horario o excepcion correspondiente para este dia
            $marcas_modulosDelimitados = array(); //describir
            if ( !empty($this->horarioExcepcionPorFecha[$fechaDate]) ){
                $timezoneOExcepcion = $this->horarioExcepcionPorFecha[$fechaDate] ;
            } else {
                $timezoneOExcepcion = (empty($this->horariosSemanalFijo[date('N', strtotime($fechaDate) )]) )? null : $this->horariosSemanalFijo[date('N', strtotime($fechaDate) )]  ; 
                $usaHorarioFijo = true; 
            }
            // FIN DATOS ESTANDAR
            
             
            /// ASIGNACION BASICA
            $datosRetorno = new Retorno(); // objeto que contiene la fila con datos a insertar o cambiar
            $datosRetorno->atndd_id = $this->usuarioId;
            $datosRetorno->atndd_date = $fechaDate;
            // FIN ASIGNACION BASICA
            
            //REGISTRAR DIA LIBRE
            if(empty($timezoneOExcepcion)) {
                $datosRetorno->atndd_result = 5;
                $datosRetorno->atndd_holiday = True;
                $this->retorno[$fechaDate] = $datosRetorno;
                return;
            }
            //FIN REGISTRAR DIA LIBRE
          
            // REGISTRAR EXCEPCION
            if( isset($timezoneOExcepcion->atndex_id )  ){
                $datosRetorno->atndd_result = 6;
                $datosRetorno->atndd_atndex_id = $timezoneOExcepcion->atndex_id ;
                $datosRetorno->horarioExcepcion = $timezoneOExcepcion; //Datos de la excepcion
                //$datosRetorno->atndd_atnd_in = $datosRetorno->atndd_atnd_out = "00000000";
                $this->retorno[$fechaDate] = $datosRetorno;
                /*
                if( empty($this->dataProcesadaPorDia[$fechaDate])){ // verificar registro previo
                    return $this->RegistrarModel($datosRetorno);
                }
                 * 
                 */
                return ;
            }
            //FIN  REGISTRAR EXCEPCION
            
            //  DELIMITAR DATOS PARA REGISTRO DE TIMEZONE  (id diferente '000000000')
            if (empty( (int)$timezoneOExcepcion->atndtz_id) ) { 
                $datosRetorno->atndd_result = 5;
                $datosRetorno->atndd_holiday = True;
                $this->retorno[$fechaDate] = $datosRetorno;
                return ;
            }
            $datosRetorno->atndd_atndtz_id = $timezoneOExcepcion->atndtz_id ;
            $datosRetorno->horarioExcepcion = $timezoneOExcepcion; // necesaria para poder saber los parametros desde la vista o controller
            $bt = $this->obtenerModuloBt($fechaDate, $timezoneOExcepcion) ;// dia laboral
            /* agrupar marcas por pares secuanciales,  en cada array(desdeDatetime,hastaDatetime)
             * de no existir marcas se registra en  estandar vacio
             * 
             * Luego de  AGRUPAR MARCAS bajo los parametros de $bt (Basic time o dia laboral),
             * las marcas no necesitan confirmar si pertenecen al horario porq ya estan filtradas
             */
            $marcas_modulosDelimitados = $this->agruparMarcas($bt[0],$bt[1])  ;
            $datosRetorno->justificador = $marcas_modulosDelimitados;// lista de marcas para dar detalles 
            //  FIN  DELIMITAR DATOS PARA REGISTRO DE TIMEZONE
            
            /// SI SOLO TIENE UNA MARCA REGISTRAR SOLO ENTRADA MAS BNO SALIDA 
            if(count($marcas_modulosDelimitados)==1  and  ($marcas_modulosDelimitados[0][0] == $marcas_modulosDelimitados[0][1]) ){
                $datosRetorno->atndd_atnd_in = (!empty($marcas_modulosDelimitados[0][0]))? date('YmdHis',$marcas_modulosDelimitados[0][0]) : null ;
                $datosRetorno->atndd_result = 4;
                $this->retorno[$fechaDate] = $datosRetorno;
                return ;
            } 
            /// FIN SI SOLO TIENE UNA MARCA REGISTRAR SOLO ENTRADA MAS BNO SALIDA
            
            /*
            // VERIFICAR REGISTRO DE DATA PROCESADA (se espera a este punto la verificacion porq se necesita ->justificador )
            if( !empty($this->dataProcesadaPorDia[$fechaDate])){
                $datosRetorno = $this->dataProcesadaPorDia[$fechaDate]; // estable  $datosRetorno Completamente nueva
                $datosRetorno->horarioExcepcion = $timezoneOExcepcion; // necesaria para poder saber los parametros desde la vista o controller
                $datosRetorno->justificador = $marcas_modulosDelimitados;// se reasigna por la la linea de arriba 
                $datosRetorno->esDatoTemporal = FALSE;
                $this->retorno[$fechaDate] = $datosRetorno;
                return true;            
            }
            // FIN VERIFICAR REGISTRO DE DATA PROCESADA
            */
                
            
            // OBTENER PARAMETROS DE MARCAS (menos $bt que esta desde antes)
            $eot =$this->obtenerModuloEot($fechaDate, $timezoneOExcepcion) ; //hora extras antes de rt
            $ot = $this->obtenerModuloOt($fechaDate, $timezoneOExcepcion) ; //hora extras despues de rt
            $rt = self::obtenerModuloRt($fechaDate, $timezoneOExcepcion) ;//horario regular 
            $nt = $this->obtenerModuloNt($fechaDate, $timezoneOExcepcion) ;//hora adicionales despues de ot
            $bono = $this->obtenerModuloBono($fechaDate, $timezoneOExcepcion);// horas con bono adicional
            // FIN OBTENER PARAMETROS DE MARCAS         
            
            /*
            // CONFIRMAR EXISTENCIA DE MARCAS
            if(count($marcas_modulosDelimitados)<1){
                $this->retorno[$fechaDate] = $datosRetorno;
                if( empty($this->dataProcesadaPorDia[$fechaDate])){ // verificar registro previo
                    $this->RegistrarModel($datosRetorno); //de no existir se guarda vacio
                }
                return false;
            } 
            // FIN CONFIRMAR EXISTENCIA DE MARCAS
            */
            
            // OBTENER MINUTOS
            $datosRetorno->atndd_min_ot +=  $this->obtenerTiempoCoincidiente($eot,$marcas_modulosDelimitados) ;
            $datosRetorno->atndd_min_ot +=  $this->obtenerTiempoCoincidiente($ot, $marcas_modulosDelimitados) ;
            $datosRetorno->atndd_min_rt +=  $this->obtenerTiempoCoincidiente($rt, $marcas_modulosDelimitados) ;
            $datosRetorno->atndd_min_nt +=  $this->obtenerTiempoCoincidiente($nt, $marcas_modulosDelimitados) ;
            $datosRetorno->atndd_min_bono = $this->obtenerTiempoCoincidiente($bono, $marcas_modulosDelimitados) ;
            $marcaMinMax = $this->obtenerMarcaMinMax($marcas_modulosDelimitados) ;
            $datosRetorno->atndd_min_total +=  $this->obtenerDiferenciaMinutos($marcaMinMax[0], $marcaMinMax[1]);
            $datosRetorno->atndd_min_ht =  $this->obtenerDiferenciaMinutos($rt[0], $rt[1]); // duracion del rt (turno)
            
            
            
            if($marcaMinMax[0] <= $rt[0] and $marcaMinMax[1] >= $rt[1]){//normal
                $datosRetorno->atndd_result = 1;        
            }
            if($marcaMinMax[0] > $rt[0]){//retraso
                $datosRetorno->atndd_min_latein = $this->obtenerDiferenciaMinutos($marcaMinMax[0], $rt[0]);
                $datosRetorno->atndd_result = 2;        
            }
            if($marcaMinMax[1] < $rt[1]){//salida temprana
                $datosRetorno->atndd_min_earlyout = $this->obtenerDiferenciaMinutos($marcaMinMax[1], $rt[1]);
                $datosRetorno->atndd_result = 3;        
            }
            if( empty($datosRetorno->justificador[0]) or count($datosRetorno->justificador[0])<1){//inacistente
                $datosRetorno->atndd_min_away =  $this->obtenerDiferenciaMinutos($rt[0], $rt[1]);
                $datosRetorno->atndd_result = 4;        
            }
            
            
            // FIN OBTENER MINUTOS
            if( !empty($marcaMinMax[1])){ 
                $datosRetorno->atndd_atnd_in = (!empty($marcaMinMax[0]))? date('YmdHis',$marcaMinMax[0]) : null ;
                $datosRetorno->atndd_atnd_out= (!empty($marcaMinMax[1]))? date('YmdHis',$marcaMinMax[1]) : null ; 
            }
                
            //// ASIGNAR VALOR OT y NT SEGUN HORARIO o GROUP 
            // var_dump($datosRetorno, $timezoneOExcepcion, $usaHorarioFijo);
            $this->limpiarLimitesTiempo($datosRetorno, $timezoneOExcepcion, $usaHorarioFijo);
            $this->retorno[$fechaDate] = $datosRetorno; 
            //// FIN ASIGNAR VALOR OT y NT SEGUN HORARIO o GROUP
            
        //    $this->RegistrarModel($datosRetorno);
            
            return ;
        } catch (Exception $e){ 
            echo  $e->getMessage() .$e->getLine() .' ERROR <br> ';
            return false;
        } 
    }
    
    private function GetHorariosTodos() {
        return $this->ci->Horarios_model->GetAllSchedule( );//$horariosIds  // tomamos todos los horarios        
    }
    
    private function getDataprocesadaPorDia($desde,$hasta,$usuarioId) {
        $temp = $this->ci->DataProcesada_model->GetProcessedDate($desde,$hasta,$this->usuarioId);
        return $this->ordenarDataProcesadaPorDia($temp);
    }
    
    private function getSchemaDiario($desde,$hasta,$usuarioId,$ignorarHorario =false,$ignorarExcepciones=false){
        return $this->ci->Horarios_model->GetUserDailySchedule($desde,$hasta,$this->usuarioId,$ignorarHorario,$ignorarExcepciones);
    }
    
    private function SetData($ignorarHorario =false,$ignorarExcepciones=false,$ignorarDataProcesada = false){
        try{            
            $desde = str_replace('-', '', $this->fechaDesde);
            $hasta = str_replace('-', '', $this->fechaHasta);
            
            if(!$ignorarDataProcesada){
    // GET DATA_PROCESADA (SI YA EXISTE el dia, no se necesita buscar en eventlog)
                $this->dataProcesadaPorDia = $this->getDataprocesadaPorDia($desde,$hasta,$this->usuarioId); 
            }
            if(!$ignorarHorario){
    // GET todos los Horarios IMPRESINDIBLE SIEMPRE . aun si tiene data procesada
                $this->horariosTodos = $this->GetHorariosTodos();
            }
            if(!$ignorarExcepciones){
    // GET excepciones 
                $this->excepcionesTodos = $this->ci->Excepciones_model->GetUserAllException();
            }
    // GET ID HORARIO  POR DIA
            $this->horarioExcepcionDiaria_IdDate = $this->getSchemaDiario($desde, $hasta, $this->usuarioId,$ignorarHorario,$ignorarExcepciones);
        // Asignar
            $this->asignarExcepcionHorarioDiario(); // Put valor en $this->horarioPorFecha
            if(!$ignorarHorario){
    //GET HORARIO FIJO        
                $this->atndGroup = $this->ci->Horarios_model->GetUserAtndGroup($this->usuarioId);
    // Teniendo atndGroup y Horarios, se puede realizar esta funcion
                $this->asignarHorarioFijo();
            }
            // Informacion de la eventlog
            $this->marcas = $this->ci->Registros_model->GetByDays($this->fechaDesde,$this->fechaHasta,$this->usuarioId ); 
            $this->ordenarMarcas( );
        } catch (Exception $e){
            return NULL;
        }
    }
     
    private function limpiarLimitesTiempo(&$retorno,$timeZone,$usaHorarioFijo) {         
        if( (int)$timeZone->atndtz_ot_daymaxmin_enable > 0){
            $retorno->atndd_min_ot = ($retorno->atndd_min_ot > $timeZone->atndtz_ot_daymaxmin_time )? $timeZone->atndtz_ot_daymaxmin_time : $retorno->atndd_min_ot ;
        } 
        
        if( (int)$timeZone->atndtz_nt_daymaxmin_enable > 0){
            $retorno->atndd_min_nt = ($retorno->atndd_min_nt > $timeZone->atndtz_nt_daymaxmin_time )? $timeZone->atndtz_nt_daymaxmin_time : $retorno->atndd_min_nt ;
        }
        
        if($usaHorarioFijo and !empty($this->atndGroup->atndgrp_ot_enable)){
            if( (int)$this->atndGroup->atndgrp_ot_enable < 1 )
                $retorno->atndd_min_ot = 0;
                
            if( (int)$this->atndGroup->atndgrp_nt_enable < 1 )
                $retorno->atndd_min_nt = 0;
        }
    }

    private function obtenerTiempoCoincidiente($moduloHorarioUnix,$marcasUnix) {
        $desde = (isset($moduloHorarioUnix[0]))? $moduloHorarioUnix[0] : 0;
        $hasta = (isset($moduloHorarioUnix[1]))? $moduloHorarioUnix[1] : 0;
        $minutos = 0;
        $justificador = array();
        
        foreach ($marcasUnix as $marcaTemp) {
            $entrada = $marcaTemp[0];
            $salida  = $marcaTemp[1];
            if( (($entrada <= $desde ) or ($entrada >= $desde and $entrada <= $hasta ))  ){
                $entradaLimpia = ($entrada < $desde)? $desde : $entrada ; 
                if($salida > $hasta){
                    $minutos += (int) $this->obtenerDiferenciaMinutos($entradaLimpia, $hasta); 
                }elseif(($salida>=$desde) and ($salida<=$hasta )  ){
                    $minutos += (int) $this->obtenerDiferenciaMinutos($entradaLimpia, $salida); 
                }
                //    var_dump(date('Ymd His',$desde),date('Ymd His',$hasta),date('Ymd His',$entrada),date('Ymd His',$salida) ,"| minutos = $minutos <br>"  ); 
            } 
        } 
        return $minutos ;
    }
    
    // retorna  array( array(timestap,timestap), ... ) ;
    private function agruparMarcas($unixMin,$unixMax) {
        $marcas = array();
        $marcasValidasParaElHorario = array();
        for  ($i=0 ; $i < count($this->marcas) ; $i++ ) {
            if( empty($this->marcas[$i])) break; 
            $marcaTemp = strtotime($this->marcas[$i]->eventdatetime);
            if( ($marcaTemp> $unixMin) and ($marcaTemp< $unixMax)){
                $marcasValidasParaElHorario[] = $marcaTemp; 
            } 
        }
        
        for  ($i=0 ; $i < count($marcasValidasParaElHorario) ; $i+=2 ) {
            if( empty($marcasValidasParaElHorario[$i])) break;
            $marcas[] = array( $marcasValidasParaElHorario[$i] ,  
                ((!empty($marcasValidasParaElHorario[$i+1]))? 
                    $marcasValidasParaElHorario[$i+1]: $marcasValidasParaElHorario[$i] ) ) ;
        }
        return $marcas;
    }
    
    private function ordenarMarcas( ) { 
        usort($this->marcas, function($a, $b) {
            return $a->eventdatetime  - $b->eventdatetime ; 
        }); 
    }
    
    private function ordenarDataProcesadaPorDia($dataProcesada) {
        $temp = array();
        foreach ($dataProcesada as $value) {
            $temp[$value->atndd_date] = $value;
        } 
        $dataProcesada = $temp;
        return $dataProcesada;
    }
    
    private function obtenerModuloEot($fechaDate , $timezoneObject){
        $desde = ''; $hasta = '';
        // or periodo antes de RT 
        if((int)$timezoneObject->atndtz_ot_earlyin_enable > 0 ) {  
            $desde = new DateTime( $fechaDate.$timezoneObject->atndtz_ot_earlyin_time ); 
            $hasta = new DateTime( $fechaDate. substr($timezoneObject->atndtz_rt_period , 0, 4));
            if($hasta < $desde ){
                $desde->sub(new DateInterval('P1D')) ;
            }
            return array($desde->getTimestamp(),$hasta->getTimestamp(),'tipo'=>'eot');
        }
        $fechaNull = new DateTime( $fechaDate);
        return array( $fechaNull->getTimestamp(),$fechaNull->getTimestamp(),'tipo'=>'eot');
    }
     
    private function obtenerModuloOt($fechaDate , $timezoneObject){ 
        $desde = ''; $hasta = ''; 
        // ot periodo despues de RT
        $desde_sub_temp =  substr($timezoneObject->atndtz_ot_period , 0, 4);
        $hasta_sub_temp =  substr($timezoneObject->atndtz_ot_period , 4, 4);
        $desde = new DateTime( $fechaDate. substr($timezoneObject->atndtz_ot_period , 0, 4));
        $hasta = new DateTime( $fechaDate. substr($timezoneObject->atndtz_ot_period , 4, 4)); 
        if($hasta < $desde ){
            $desde->sub(new DateInterval('P1D')) ;
        }
        return array($desde->getTimestamp(),$hasta->getTimestamp(),'tipo'=>'ot');
    } 
    
    static function obtenerModuloRt($fechaDate , $timezoneObject){ 
        $desde = ''; $hasta = '';
        // CALCULO de RT  
        $desde = new DateTime( $fechaDate. substr($timezoneObject->atndtz_rt_period , 0, 4));
        $hasta = new DateTime( $fechaDate. substr($timezoneObject->atndtz_rt_period , 4, 4));
        if($hasta < $desde ){
            $desde->sub(new DateInterval('P1D')) ;
        }
        return array($desde->getTimestamp(),$hasta->getTimestamp(),'tipo'=>'rt'); 
    }
    
    private function obtenerModuloNt($fechaDate , $timezoneObject){ 
        $desde = ''; $hasta = '';
        // CALCULO de RT  
        $desde = new DateTime( $fechaDate. substr($timezoneObject->atndtz_nt_period , 0, 4));
        $hasta = new DateTime( $fechaDate. substr($timezoneObject->atndtz_nt_period , 4, 4));
        if($hasta < $desde ){
            $desde->sub(new DateInterval('P1D')) ;
        }
        return array($desde->getTimestamp(),$hasta->getTimestamp(),'tipo'=>'nt'); 
    }
     
    private function obtenerModuloBt($fechaDate , $timezoneObject){ 
        $desde = ''; $hasta = ''; 
        $desde = new DateTime( $fechaDate.substr($timezoneObject->atndtz_bt_period , 0, 4)); 
        $temp  = new DateTime( $fechaDate.substr($timezoneObject->atndtz_bt_period ,  4,4) ); 
        if( $desde > $temp   ){
            $temp->add(new DateInterval('P1D'));
            $hasta = $temp ; 
        }else{
            $hasta = $temp;
        } 
        return  array($desde->getTimestamp(),$hasta->getTimestamp(),'tipo'=>'bt');
    }
    
    private function obtenerModuloBono($fechaDate , $timezoneObject){ 
        $desde = ''; $hasta = ''; 
        $default = substr('00000000'.$timezoneObject->atndtz_bono_period , -8,8);
        $desde = new DateTime( $fechaDate.substr($default, 0, 4)); 
        $temp  = new DateTime( $fechaDate.substr($default,  4,4) ); 
        if( $desde > $temp   ){
            $temp->add(new DateInterval('P1D'));
            $hasta = $temp ; 
        }else{
            $hasta = $temp;
        } 
        return  array($desde->getTimestamp(),$hasta->getTimestamp(),'tipo'=>'bono');
    }
         
    private function obtenerMarcaMinMax($marcasUnix) {
        if (empty($this->marcas))return array(0,0); 
        if (count($this->marcas)<1)return array(0,0);  
        
        $minUnix = time();
        $maxUnix = 0;
        foreach ($marcasUnix as $marcaTemp) {
            $entrada = $marcaTemp[0];
            $salida = $marcaTemp[1];
            $minUnix = ($entrada < $minUnix)? $entrada : $minUnix;
            $maxUnix = ($salida > $maxUnix)? $salida : $maxUnix;
        } 
        if($maxUnix==0){
            return array(0,0);
        }
        //var_dump(date('Ymd -- His',$minUnix  ),date('Ymd -- His',$maxUnix  ));
        return array($minUnix,$maxUnix);
    }
    
    private function ConvertirMinutosHorasString(  $minutos){
        $minutos = (int)$minutos;
        return ((int)($minutos/60)).':' .($minutos%60) ;
    }
      
    private  function buscarHorarioPorId($id){
        foreach ($this->horariosTodos as $value) {
            if($id == $value->atndtz_id )
                return $value ;
        }
        return null;
    } 
    
    private function obtenerDiferenciaMinutos($desdeDatetime,$hastaDatetime) {
        try{            
            $fecha =  (strlen($desdeDatetime)<=10)? new DateTime(date('YmdHis',$desdeDatetime)): new DateTime($desdeDatetime) ;
            $fecha2 = (strlen($hastaDatetime)<=10)? new DateTime(date('YmdHis',$hastaDatetime)): new DateTime($hastaDatetime) ;
        } catch(Exception $e) {return 0;}
        
        $interval = $fecha->diff($fecha2);
        $horas = (int) $interval->format('%H'); 
        $minutos = (int) $interval->format('%i'); 
        $tiempo = ($horas*60) + $minutos;
        return $tiempo;
    }
    
    // asigna prioritariamente sobre el horario, la excepcion de un dia(Permisos etc)
    private function asignarExcepcionHorarioDiario() {       
        if (empty($this->horariosTodos))return null;   
        foreach ($this->horarioExcepcionDiaria_IdDate as $idFechaTemp) { 
            foreach ($this->horariosTodos as $horariosTodosTemp) {
                if($idFechaTemp->atnduds_s1_timezoneid == $horariosTodosTemp->atndtz_id){
                    $this->horarioExcepcionPorFecha[$idFechaTemp->atnduds_date] = $horariosTodosTemp;  
                }
            }  
            foreach ($this->excepcionesTodos as $excepcionesTodosTemp) {
                if( ( (int)$excepcionesTodosTemp->atndex_id != 0) and ($idFechaTemp->atnduds_exception_id == $excepcionesTodosTemp->atndex_id)){
                    $this->horarioExcepcionPorFecha[$idFechaTemp->atnduds_date] = $excepcionesTodosTemp;                    
                }
            } 
        } 
        return true;
    } 
    
    // Teniendo atndGroup y Horarios, se puede realizar esta funcion
    private function asignarHorarioFijo() {// var_dump($this->atndGroup);
        if (empty($this->horariosTodos))return null; 
        if (empty($this->atndGroup))return null; 

         
        $this->horariosSemanalFijo[1] = $this->buscarHorarioPorId($this->atndGroup->atndgrp_s1timezoneofmon);
        $this->horariosSemanalFijo[2] = $this->buscarHorarioPorId($this->atndGroup->atndgrp_s1timezoneoftue);
        $this->horariosSemanalFijo[3] = $this->buscarHorarioPorId($this->atndGroup->atndgrp_s1timezoneofwed);
        $this->horariosSemanalFijo[4] = $this->buscarHorarioPorId($this->atndGroup->atndgrp_s1timezoneofthu);
        $this->horariosSemanalFijo[5] = $this->buscarHorarioPorId($this->atndGroup->atndgrp_s1timezoneoffri);
        $this->horariosSemanalFijo[6] = $this->buscarHorarioPorId($this->atndGroup->atndgrp_s1timezoneofsat);
        $this->horariosSemanalFijo[7] = $this->buscarHorarioPorId($this->atndGroup->atndgrp_s1timezoneofsun);  
    } 
    
    private function limpiarElementosAdicionales($datos) {
        $datos = (array)$datos;
        if(!$datos['esDatoTemporal']) return null;
        unset($datos['esDatoTemporal'],$datos['justificador'],$datos['horarioExcepcion']);
        return $datos;
         // esos  Valores no corresponden en la base de datos
    }
    
    public function RegistrarModel( $datos) {        
        $datos = $this->limpiarElementosAdicionales($datos);
        $this->ci->DataProcesada_model->SetProcessedDate($datos); 
    }
    
    public function ActualizarDatosModel($datos) {
        
    }
}

