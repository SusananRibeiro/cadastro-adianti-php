<?php 

// app/lib/ConvertCurrency.php

class ConvertCurrency {
    /**
     * Converte um valor de moeda do formato brasileiro (1.234,56) para o formato EUA (1234.56).
     * 
     * @param string $currency Valor da moeda no formato brasileiro
     * @return string Valor da moeda no formato EUA
     */
    public static function toUSFormat($currency) {
        // Remove pontos e substitui vírgula por ponto
        $currency = str_replace('.', '', $currency);
        $currency = str_replace(',', '.', $currency);
        return $currency;
    }
    
    public static function toBRFormat($currency) {
        return number_format($currency, 2, ',', '.');
    }
}


?>