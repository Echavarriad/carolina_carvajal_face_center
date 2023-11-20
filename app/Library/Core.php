<?php

	namespace App\Library;

	class Core{
        public function getMonthsLarge(){
            return array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        }

        public function getMonthsShort(){
            return array("Ene","Feb","Mar","Abr","May","Jun","Jul","Agos","Sept","Oct","Nov","Dic");
        }

        public function getDays(){
            return array("Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado");
            
        }
    }