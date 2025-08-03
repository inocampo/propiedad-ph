<?php

return [
    'components' => [
        'checkbox' => [
            'boolean' => [
                'true' => 'Sí',
                'false' => 'No',
            ],
        ],
        'file_upload' => [
            'editor' => [
                'actions' => [
                    'cancel' => [
                        'label' => 'Cancelar',
                    ],
                    'drag_crop' => [
                        'label' => 'Modo arrastar "recortar"',
                    ],
                    'drag_move' => [
                        'label' => 'Modo arrastar "mover"',
                    ],
                    'flip_horizontal' => [
                        'label' => 'Voltear imagen horizontalmente',
                    ],
                    'flip_vertical' => [
                        'label' => 'Voltear imagen verticalmente',
                    ],
                    'move_down' => [
                        'label' => 'Mover imagen hacia abajo',
                    ],
                    'move_left' => [
                        'label' => 'Mover imagen hacia la izquierda',
                    ],
                    'move_right' => [
                        'label' => 'Mover imagen hacia la derecha',
                    ],
                    'move_up' => [
                        'label' => 'Mover imagen hacia arriba',
                    ],
                    'reset' => [
                        'label' => 'Restablecer',
                    ],
                    'rotate_left' => [
                        'label' => 'Rotar imagen hacia la izquierda',
                    ],
                    'rotate_right' => [
                        'label' => 'Rotar imagen hacia la derecha',
                    ],
                    'save' => [
                        'label' => 'Guardar',
                    ],
                    'zoom_100' => [
                        'label' => 'Hacer zoom a imagen al 100%',
                    ],
                    'zoom_in' => [
                        'label' => 'Acercar',
                    ],
                    'zoom_out' => [
                        'label' => 'Alejar',
                    ],
                ],
            ],
        ],
        'select' => [
            'actions' => [
                'create_option' => [
                    'label' => 'Crear',
                ],
                'edit_option' => [
                    'label' => 'Editar',
                ],
            ],
            'boolean' => [
                'true' => 'Sí',
                'false' => 'No',
            ],
            'loading_message' => 'Cargando...',
            'max_items_message' => 'Solo se pueden seleccionar :count elementos.',
            'no_search_results_message' => 'No se encontraron opciones que coincidan con tu búsqueda.',
            'placeholder' => 'Selecciona una opción',
            'searching_message' => 'Buscando...',
            'search_prompt' => 'Comienza a escribir para buscar...',
        ],
        'wizard' => [
            'actions' => [
                'previous_step' => [
                    'label' => 'Anterior',
                ],
                'next_step' => [
                    'label' => 'Siguiente',
                ],
            ],
        ],
    ],
];