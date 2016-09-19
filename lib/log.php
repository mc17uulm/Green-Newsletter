<?php
/**
 * Created by PhpStorm.
 * User: Marco
 * Date: 28.08.2016
 * Time: 11:03
 */

function logger($line, $type, $message)
{

    $format = "csv"; //Moeglichkeiten: csv und txt

    $datum_zeit = date("d.m.Y H:i:s");
    $ip = $_SERVER["REMOTE_ADDR"];

    $monate = array(1 => "Januar", 2 => "Februar", 3 => "Maerz", 4 => "April", 5 => "Mai", 6 => "Juni", 7 => "Juli", 8 => "August", 9 => "September", 10 => "Oktober", 11 => "November", 12 => "Dezember");
    $monat = date("n");
    $jahr = date("y");

    $dateiname = "logs/log_" . $monate[$monat] . "_$jahr.$format";

    $header = array("Datum", "IP", "Code", "Type", "Message");
    $infos = array($datum_zeit, $ip, $line, $type, $message);

    if ($format == "csv") {
        $eintrag = implode('; ', $infos);
    } else {
        $eintrag = implode("\t", $infos);
    }

    $write_header = !file_exists($dateiname);

    $datei = fopen($dateiname, "a");

    if ($write_header) {
        if ($format == "csv") {
            $header_line = implode('; ', $header);
        } else {
            $header_line = implode("\t", $header);
        }

        fputs($datei, $header_line . "\n");
    }

    fputs($datei, $eintrag . "\n");
    fclose($datei);
}
?>