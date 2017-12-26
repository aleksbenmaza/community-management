<?php

/**
 * Created by PhpStorm.
 * User: alexandremasanes
 * Date: 25/07/2016
 * Time: 22:05
 */

namespace Application\Http\Response;

abstract class Response{
    protected static 
        $codes = [
            200 => 'OK',
            400 => 'Bad Request',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            503 => 'Service Unavailable',
            600 => 'Missing services'
        ],

        $messages = [
            400 => 'Echec de l\'analyse HTTP !',
            403 => 'Accès interdit !',
            405 => 'Methode non autorisée',
            404 => 'La page n\'existe pas ou plus !',
            503 => 'Cette page est inaccessible pour le momment.<br/>
                   Nous vous remercions de bien vouloir patienter.',
            600 => 'JavaScript est désactivé sur votre navigateur.<br/>
                  Veuillez l\'activer pour continuer à naviguer'
        ];

    const
        FORBIDDEN = 403,
        NOT_FOUND = 404,
        METHOD_NOT_ALLOWED = 405,
        SERVICE_UNAVAILABLE = 503,
        BAD_REQUEST = 400,
        MISSING_SERVICES = 600;
    
    public abstract function __toString(): string;
}