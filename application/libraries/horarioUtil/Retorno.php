<?php

 /*
  * estructura de datos que debe ser guardada o modificada
  * en la que se contiene la informacion de cada dia laborado de DATA PROCESADA
  */
class Retorno{
    public function __construct() {
        $this->atndd_created = $this->atndd_updated = date('YmdHis');
    }
    public  
        $atndd_date, 
        $atndd_id,
        $atndd_created , // fecha del dia
        $atndd_updated,  // fecha del dia
        $atndd_holiday = false, // es feriado ?  0 o 1  tipo bit y result=5
        $atndd_atndtz_id = '0000000000000000',
        $atndd_atndex_id = '0',
        $atndd_result  = 1, // regular=1, late in=2,salida temprana=3 ,Absent=4 , holiday =5 ,excepcion=6   
        $atndd_modified = false, // fue modificado ? tipo bit
        $atndd_desc  ,
        $atndd_event_in,
        $atndd_event_out,
        $atndd_atnd_in,
        $atndd_atnd_out,
        $atndd_event_away_in,
        $atndd_event_away_out,
        $atndd_atnd_away_in,
        $atndd_atnd_away_out,
        $atndd_min_total = 0 ,
        $atndd_min_rt = 0 ,
        $atndd_min_ot = 0 ,
        $atndd_min_nt = 0 ,
        $atndd_min_ht = 0 ,
        $atndd_min_bono = 0 ,    
        $atndd_min_latein = 0 ,
        $atndd_min_earlyout = 0 ,
        $atndd_min_away = 0 ,
        $atndd_min_meal = 0 ,
        $atndd_atnd_meal_in,
        $atndd_atnd_meal_out,
        $atndd_event_meal_in,
        $atndd_event_meal_out,
     //   $nombreEditor = null,//Existe en DB, nombre de la persona que realizo la edicion
        $justificador = array(),
        $horarioExcepcion    , 
        $esDatoTemporal = true  ;
        
}