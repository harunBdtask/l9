<?php

if (!function_exists('in_words')) {
    function in_words($number): string
    {
        if (($number < 0) || ($number > 999999999)) {
            throw new Exception("Number is out of range");
        }

        $kt = floor($number / 10000000); /* Koti */
        $number -= $kt * 10000000;
        $gn = floor($number / 100000);  /* lakh  */
        $number -= $gn * 100000;
        $kn = floor($number / 1000);     /* Thousands (kilo) */
        $number -= $kn * 1000;
        $hn = floor($number / 100);      /* Hundreds (hecto) */
        $number -= $hn * 100;
        $dn = floor($number / 10);       /* Tens (deca) */
        $n = $number % 10;               /* Ones */

        $res = "";

        if ($kt) {
            $res .= in_words($kt) . " Crore ";
        }

        if ($gn) {
            $res .= in_words($gn) . " Lac";
        }

        if ($kn) {
            $res .= (empty($res) ? "" : " ") . in_words($kn) . " Thousand";
        }

        if ($hn) {
            $res .= (empty($res) ? "" : " ") . in_words($hn) . " Hundred";
        }

        $ones = [
            "",
            "One",
            "Two",
            "Three",
            "Four",
            "Five",
            "Six",
            "Seven",
            "Eight",
            "Nine",
            "Ten",
            "Eleven",
            "Twelve",
            "Thirteen",
            "Fourteen",
            "Fifteen",
            "Sixteen",
            "Seventeen",
            "Eighteen",
            "Nineteen"
        ];

        $tens = [
            "",
            "",
            "Twenty",
            "Thirty",
            "Forty",
            "Fifty",
            "Sixty",
            "Seventy",
            "Eighty",
            "Ninety"
        ];

        if ($dn || $n) {
            if (!empty($res)) {
                $res .= " and ";
            }

            if ($dn < 2) {
                $res .= $ones[$dn * 10 + $n];
            } else {
                $res .= $tens[$dn];

                if ($n) {
                    $res .= "-" . $ones[$n];
                }
            }
        }

        if (empty($res)) {
            $res = "zero";
        }

        return $res;
    }
}
