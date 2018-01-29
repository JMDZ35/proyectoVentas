<?php

/*
 *  
 * 
 * 
 */
class Utilitario {
    
    
    /*
     * Verificar si existe el modulo correspondiente a travez de la uri
     */
    public static function moduloEstaDisponible($uri='',array $listaModulos=array()) {
        $uri = strtolower($uri);
        foreach ($listaModulos as $modulo) {
            if(strtolower($modulo->uri) == $uri  ){ 
                return true;
            }
           // echo " $modulo->uri : $uri <br> ";
        } 
        return false;
    }
    
    
    // DELIMITAR UBICACIONES
    public static function tienePermisosAreas($id,$type,$RestriccionUbicaciones){
        foreach ($RestriccionUbicaciones as $value) {
            $value = (object)$value;
            if((int)$value->areaId === (int)$id and (int)$value->area === (int)$type )
                return true;
        }
        return FALSE;
    }
    
    /*
     * se le pasa una fecha formato Y-m-d y de ser correcto retorna su valor 
     * de lo contrario el valor por $default
     */
    public static function checkFecha($fecha,$default){
        if(!empty($fecha) ){
            $fecha = (strlen($fecha)== 8)? substr($fecha,0,4)."-".substr($fecha,4,2)."-".substr($fecha,6,2) : NULL;
            $fecha = explode('-', $fecha);
            if((count($fecha)== 3) and (checkdate($fecha[1], $fecha[2], $fecha[0]))  ){
                $fecha = (new DateTime(implode('-', $fecha)))->format('Ymd'); 
            }else{
                $fecha = $default;
            }
        }else{
            $fecha = $default;
        }
        return $fecha;
    }
    
    
    public static  function limpiarCaracteresEspeciales($string) {
        return  str_replace(array('-','-','.',',','º','\\','_','/',"'",'"'), '', $string);
    }
    
    public static function Tiempo_microtime_float() {
        list($useg, $seg) = explode(" ", microtime());
        return ((float)$useg + (float)$seg);
    }
    
    static function sistemaOperativo(){
        $return = strtoupper(substr(PHP_OS, 0, 3));
        if ($return   === 'WIN') {
            return 2;
        } elseif($return === 'Lin') {
            return 1;
        } else {
            return 3;
        }
    }                       
    
    public static function ping($host , $timeout=1000) { 
        $consulta = (self::sistemaOperativo()==1)? 'ping '.$host.' -W '.$timeout.' -n 1' : 'ping '.$host.' -w '.$timeout.' -n 1'  ;
        try{
            $lineas = array();
            $result = strtolower(exec($consulta,$lineas));
            $result = implode(' ',$lineas);
            $buscar = 'recibidos = 1';  //Received = 0
            //echo "  $result $buscar ";
            if(strstr($result, $buscar) === false and strstr($result, 'Received = 0') === false){ 
                return FALSE;
            } else {
                return TRUE;
            }
        } catch (Exception $e){
            return FALSE;
        }
    }
     


    
   
   public static function formatoFecha($fechaMarcacion1){              

        $año= substr($fechaMarcacion1,0,-4);         
        $mes=substr($fechaMarcacion1,4,-2);
        $dia=substr($fechaMarcacion1,6);
        
        
        return $año.'/'.$mes.'/'.$dia;
   }

   public static function formatoHora($horaMarcacion1){
          $validoCero= substr($horaMarcacion1,0,-4);
        if($validoCero==0){
             $hora= substr($horaMarcacion1,1,-4);
        }else{
             $hora= substr($horaMarcacion1,0,-4);
        }
      
        $minutos=substr($horaMarcacion1,2,-2);
        $segundos=substr($horaMarcacion1,4);
        
        
        return $hora.':'.$minutos.':'.$segundos;
   }
   


   
   

    public static function toHours($min,$type ='')
    { //obtener segundos
    $sec = $min * 60;
    //dias es la division de n segs entre 86400 segundos que representa un dia
    $dias=floor($sec/86400);
    //mod_hora es el sobrante, en horas, de la division de días; 
    $mod_hora=$sec%86400;
    //hora es la division entre el sobrante de horas y 3600 segundos que representa una hora;
    $horas=floor($mod_hora/3600); 
    //mod_minuto es el sobrante, en minutos, de la division de horas; 
    $mod_minuto=$mod_hora%3600;
    //minuto es la division entre el sobrante y 60 segundos que representa un minuto;
    $minutos=floor($mod_minuto/60);
    if($horas<=0)
    {
    $text = $minutos.' min';
    }
    elseif($dias<=0)
    {
    if($type=='round')
    //nos apoyamos de la variable type para especificar si se muestra solo las horas
    {
    $text = $horas.' hrs';
    }
    else
    {
    $text = $horas." hrs ".$minutos;
    }
    }
    else
    {
    //nos apoyamos de la variable type para especificar si se muestra solo los dias
    if($type=='round')
    {
    $text = $dias.' dias';
    }
    else
    {
    $text = $dias." dias ".$horas." hrs ".$minutos." min";
    }
    }
    return $text; 
    }
    
}
