<?php

return [
    'components' => [
        'account' => [
            'heading' => 'Mi cuenta',
        ],
    ],
    'login' => [
        'heading' => 'Iniciar sesión',
        'actions' => [
            'authenticate' => [
                'label' => 'Iniciar sesión',
            ],
        ],
        'fields' => [
            'email' => [
                'label' => 'Dirección de correo electrónico',
            ],
            'password' => [
                'label' => 'Contraseña',
            ],
            'remember' => [
                'label' => 'Recordarme',
            ],
        ],
        'messages' => [
            'failed' => 'Estas credenciales no coinciden con nuestros registros.',
        ],
    ],
    'logout' => [
        'label' => 'Cerrar sesión',
    ],
    'pages' => [
        'dashboard' => [
            'title' => 'Panel de Control',
        ],
    ],
];