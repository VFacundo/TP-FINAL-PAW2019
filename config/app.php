<?php
return [
    'Router' => [
        'accionPorDefecto' => [
            'controlador' => 'Login',
            'accion' => 'iniciarsesion'
        ]
    ],
    'login' => [
        'accionPorDefecto' => [
            'controlador' => 'Login',
            'accion' => 'iniciarsesion'
        ]
    ],
    'perfil' => [
        'accionPorDefecto' => [
            'controlador' => 'Perfil',
            'accion' => 'perfil'
        ]
    ],
    'response' => [
        'accionPorDefecto' => [
            'controlador' => 'Response',
            'accion' => 'responsepage'
        ]
    ],
    'equipo' => [
        'accionPorDefecto' => [
            'controlador' => 'Equipo',
            'accion' => 'miequipo'
        ]
    ],
    'turnos' => [
        'accionPorDefecto' => [
            'controlador' => 'Turnos',
            'accion' => 'misturnos'
        ]
    ],
    'instantsearch' => [
        'accionPorDefecto' => [
            'controlador' => 'Instantsearch',
            'accion' => 'search'
        ]
    ]
];
