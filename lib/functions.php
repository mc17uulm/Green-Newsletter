<?php
/**
 * Created by PhpStorm.
 * User: Marco
 * Date: 15.08.2016
 * Time: 19:16
 */

function getKey($int){
    $length = mb_strlen($int);
    $tmp = (String) $int;
    $final = array();
    for($i = 0; $i < $length; $i++){
        $four = inFourBitBin($tmp[$i]);
        $rev = returnBin($four);
        $dec = binToDec($rev);
        $dec += 240;
        $bin = inEightBitBin($dec);
        $out = array();
        for($j = 0; $j < 4; $j++){
            $tmp_arr = array($bin[$j*2], $bin[($j*2)+1]);
            array_push($out, binToDec($tmp_arr));
        }
        array_push($final, implode($out));
    }
    return implode($final);
}


function getID($int){
    $length = mb_strlen($int);
    $arr = array();
    $str = (String) $int;
    for($i = 0; $i < $length; $i++){
        $tmp = inTwoBitBin($str[$i]);
        array_push($arr, $tmp[0]);
        array_push($arr, $tmp[1]);
    }
    $out = array();
    for($j = 0; $j < (count($arr)/8); $j++){
        $num = array_slice($arr, ($j*8), 8);
        $dec = binToDec($num);
        $dec = $dec - 240;
        $bin = inFourBitBin($dec);
        $rev = returnBin($bin);
        $dez = binToDec($rev);
        array_push($out, $dez);
    }
    return implode($out);
}

function intToBin($int){
    $tmp = array();
    while($int != 0){
        array_push($tmp, $int%2);
        $int = floor($int/2);
    }
    return array_reverse($tmp);
}

function inTwoBitBin($int){
    $arr = intToBin($int);
    if(count($arr) < 2){
        $tmp = array_reverse($arr);
        for($i = 0; $i < (2 - count($arr)); $i++){
            array_push($tmp, 0);
        }
        $arr = array_reverse($tmp);
    }
    return $arr;
}

function inFourBitBin($int){
    $arr = intToBin($int);
    if(count($arr) < 4){
        $tmp = array_reverse($arr);
        for($i = 0; $i < (4 - count($arr)); $i++){
            array_push($tmp, 0);
        }
        $arr = array_reverse($tmp);
    }
    return $arr;
}

function returnBin($bin){
    return array_reverse($bin);
}

function inEightBitBin($int){
    $arr = intToBin($int);
    if(count($arr) < 4){
        $tmp = array_reverse($arr);
        for($i = 0; $i < (8 - count($arr)); $i++){
            array_push($tmp, 0);
        }
        $arr = array_reverse($tmp);
    }
    return $arr;
}

function binToDec($bin){
    $index = 1;
    $sum = 0;
    for($i = count($bin) - 1; $i >= 0; $i--){
        $sum += $bin[$i] * $index;
        $index = $index * 2;
    }
    return $sum;
}

function getBool($val){
    if($val == "1"){
        return "checked";
    } else{
        return "";
    }
}