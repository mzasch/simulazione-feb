<?php

/**
 * Used to store website configuration information.
 *
 * @var string or null
 */
function config($key = '')
{
    $config = [
        'name' => 'Simulazione di seconda prova',
        'subtitle' => '28 febbraio 2019',
        'site_url' => 'http://localhost/simulazione-feb',
        'pretty_uri' => true,
        'nav_menu' => [
            '' => 'Home',
            'stato-bici' => 'Bici in uso',
            'scelta-stazione' => 'Lista Stazioni',
            'scelta-report' => 'Report utente',
        ],
        'template_path' => 'template',
        'content_path' => 'content',
        'version' => 'v1.0',
    ];

    return isset($config[$key]) ? $config[$key] : null;
}
