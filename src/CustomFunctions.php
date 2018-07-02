<?php

namespace App;

use Symfony\Component\HttpFoundation\Response;

class CustomFunctions
{
    public static function respondWithJSON($array)
    {
        $response = new Response();
        $response->setContent(json_encode($array));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', 'http://localhost:3000');
        return $response;
    }

    public static function normalize($string)
    {
        // Mise en minuscules
        $string = strtolower($string);

        // Caractères à remplacer par des _
        $charsToReplace = array(' ', '-');
        $string = str_replace($charsToReplace,'_',$string);

        // Lettres spéciales
        $ls = array('ä', 'à', 'â', 'å', 'á');
        $string = str_replace($ls, 'a', $string);

        $ls = array('é', 'è', 'ë', 'ê');
        $string = str_replace($ls, 'e', $string);

        $ls = array('î', 'ï');
        $string = str_replace($ls, 'i', $string);

        $ls = array('ö', 'ô','ø');
        $string = str_replace($ls, 'o', $string);

        $ls = array('ù', 'û', 'ü', 'ú');
        $string = str_replace($ls, 'u', $string);

        $string = str_replace('æ', 'ae', $string);
        $string = str_replace('œ', 'oe', $string);
        $string = str_replace('ç', 'c', $string);
        $string = str_replace('ÿ', 'y', $string);

        // Caractères à supprimer
        $string = preg_replace('/[^a-zA-Z\d\s_]/', '', $string);
        $string = preg_replace('/[_]{2,}/', '_', $string);

        return $string;
    }
}