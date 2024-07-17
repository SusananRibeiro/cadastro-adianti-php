<?php 
// Colocar depois --> app/lib/DateHelper.php

class ConvertDate {
    /**
     * Converte uma data do formato dd/mm/yyyy para o formato yyyy-mm-dd.
     * 
     * @param string $date Data no formato dd/mm/yyyy
     * @return string|bool Data no formato yyyy-mm-dd ou FALSE em caso de erro
     */
    public static function toUSFormat($date) {
        if (!empty($date)) {
            $date_parts = explode('/', $date);
            if (count($date_parts) == 3) {
                return $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];
            }
        }
        return false;
    }

    public static function toBRFormat($date) {
        if (!empty($date)) {
            $date_parts = explode('-', $date);
            if (count($date_parts) == 3) {
                return $date_parts[2] . '/' . $date_parts[1] . '/' . $date_parts[0];
            }
        }
        return false;
    }
}


?>


