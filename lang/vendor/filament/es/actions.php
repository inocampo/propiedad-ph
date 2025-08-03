<?php

return [
    'attach' => [
        'single' => [
            'label' => 'Adjuntar',
            'modal' => [
                'heading' => 'Adjuntar :label',
                'fields' => [
                    'record_id' => [
                        'label' => 'Registro',
                    ],
                ],
                'actions' => [
                    'attach' => [
                        'label' => 'Adjuntar',
                    ],
                    'attach_another' => [
                        'label' => 'Adjuntar y adjuntar otro',
                    ],
                ],
            ],
        ],
    ],
    'associate' => [
        'single' => [
            'label' => 'Asociar',
            'modal' => [
                'heading' => 'Asociar :label',
                'fields' => [
                    'record_id' => [
                        'label' => 'Registro',
                    ],
                ],
                'actions' => [
                    'associate' => [
                        'label' => 'Asociar',
                    ],
                    'associate_another' => [
                        'label' => 'Asociar y asociar otro',
                    ],
                ],
            ],
        ],
    ],
    'create' => [
        'single' => [
            'label' => 'Crear',
            'modal' => [
                'heading' => 'Crear :label',
                'actions' => [
                    'create' => [
                        'label' => 'Crear',
                    ],
                    'create_another' => [
                        'label' => 'Crear y crear otro',
                    ],
                ],
            ],
        ],
    ],
    'delete' => [
        'single' => [
            'label' => 'Eliminar',
            'modal' => [
                'heading' => 'Eliminar :label',
                'actions' => [
                    'delete' => [
                        'label' => 'Eliminar',
                    ],
                ],
            ],
        ],
        'multiple' => [
            'label' => 'Eliminar seleccionados',
            'modal' => [
                'heading' => 'Eliminar :label seleccionados',
                'actions' => [
                    'delete' => [
                        'label' => 'Eliminar',
                    ],
                ],
            ],
        ],
    ],
    'detach' => [
        'single' => [
            'label' => 'Separar',
            'modal' => [
                'heading' => 'Separar :label',
                'actions' => [
                    'detach' => [
                        'label' => 'Separar',
                    ],
                ],
            ],
        ],
        'multiple' => [
            'label' => 'Separar seleccionados',
            'modal' => [
                'heading' => 'Separar :label seleccionados',
                'actions' => [
                    'detach' => [
                        'label' => 'Separar',
                    ],
                ],
            ],
        ],
    ],
    'dissociate' => [
        'single' => [
            'label' => 'Desasociar',
            'modal' => [
                'heading' => 'Desasociar :label',
                'actions' => [
                    'dissociate' => [
                        'label' => 'Desasociar',
                    ],
                ],
            ],
        ],
        'multiple' => [
            'label' => 'Desasociar seleccionados',
            'modal' => [
                'heading' => 'Desasociar :label seleccionados',
                'actions' => [
                    'dissociate' => [
                        'label' => 'Desasociar',
                    ],
                ],
            ],
        ],
    ],
    'edit' => [
        'single' => [
            'label' => 'Editar',
            'modal' => [
                'heading' => 'Editar :label',
                'actions' => [
                    'save' => [
                        'label' => 'Guardar cambios',
                    ],
                ],
            ],
        ],
    ],
    'export' => [
        'single' => [
            'label' => 'Exportar',
            'modal' => [
                'heading' => 'Exportar :label',
                'form' => [
                    'columns' => [
                        'label' => 'Columnas',
                        'form' => [
                            'is_enabled' => [
                                'label' => ':column habilitado',
                            ],
                            'label' => [
                                'label' => 'Etiqueta de :column',
                            ],
                        ],
                    ],
                ],
                'actions' => [
                    'export' => [
                        'label' => 'Exportar',
                    ],
                ],
            ],
        ],
    ],
    'force_delete' => [
        'single' => [
            'label' => 'Eliminar permanentemente',
            'modal' => [
                'heading' => 'Eliminar permanentemente :label',
                'actions' => [
                    'delete' => [
                        'label' => 'Eliminar',
                    ],
                ],
            ],
        ],
        'multiple' => [
            'label' => 'Eliminar seleccionados permanentemente',
            'modal' => [
                'heading' => 'Eliminar permanentemente :label seleccionados',
                'actions' => [
                    'delete' => [
                        'label' => 'Eliminar',
                    ],
                ],
            ],
        ],
    ],
    'import' => [
        'single' => [
            'label' => 'Importar',
            'modal' => [
                'heading' => 'Importar :label',
                'form' => [
                    'file' => [
                        'label' => 'Archivo',
                        'placeholder' => 'Subir un archivo CSV',
                    ],
                    'columns' => [
                        'label' => 'Columnas',
                        'placeholder' => 'Selecciona una columna',
                    ],
                ],
                'actions' => [
                    'download_example' => [
                        'label' => 'Descargar archivo CSV de ejemplo',
                    ],
                    'import' => [
                        'label' => 'Importar',
                    ],
                ],
            ],
        ],
    ],
    'replicate' => [
        'single' => [
            'label' => 'Replicar',
        ],
    ],
    'restore' => [
        'single' => [
            'label' => 'Restaurar',
            'modal' => [
                'heading' => 'Restaurar :label',
                'actions' => [
                    'restore' => [
                        'label' => 'Restaurar',
                    ],
                ],
            ],
        ],
        'multiple' => [
            'label' => 'Restaurar seleccionados',
            'modal' => [
                'heading' => 'Restaurar :label seleccionados',
                'actions' => [
                    'restore' => [
                        'label' => 'Restaurar',
                    ],
                ],
            ],
        ],
    ],
    'view' => [
        'single' => [
            'label' => 'Ver',
        ],
    ],
];