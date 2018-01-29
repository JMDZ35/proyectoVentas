$(document).ready(function(){
 	
 var ubicacion = $('#ubicacion').val();       
        
        
        
        
        
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

        
        
        

        momentoActual = new Date() ;
   	hora = momentoActual.getHours() ;
   	minuto = momentoActual.getMinutes() ;
   	segundo = momentoActual.getSeconds() ;
        fechaImprimible =diasSemana[f.getDay()] + ", " + f.getDate() + " de " + meses[f.getMonth()] + " del " + f.getFullYear() ;
   	horaImprimible = hora + " : " + minuto  ;
        $("#fecha").val(fechaImprimible);
        $("#hora").val(horaImprimible);
        
   	 
        
});