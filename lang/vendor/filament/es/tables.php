<?php

return [
    'columns' => [
        'select_all' => [
            'label' => 'Seleccionar todo',
        ],
    ],
    'selection_indicator' => [
        'selected_count' => '1 registro seleccionado|:count registros seleccionados',
        'actions' => [
            'select_all' => [
                'label' => 'Seleccionar los :count',
            ],
            'deselect_all' => [
                'label' => 'Deseleccionar todo',
            ],
        ],
    ],
    'actions' => [
        'disable_reordering' => [
            'label' => 'Terminar reordenamiento de registros',
        ],
        'enable_reordering' => [
            'label' => 'Reordenar registros',
        ],
        'filter' => [
            'label' => 'Filtrar',
        ],
        'group' => [
            'label' => 'Agrupar',
        ],
        'open_bulk_actions' => [
            'label' => 'Acciones masivas',
        ],
        'toggle_columns' => [
            'label' => 'Alternar columnas',
        ],
    ],
    'empty' => [
        'heading' => 'No se encontraron registros',
        'description' => 'Crea un :model para comenzar.',
    ],
    'filters' => [
        'actions' => [
            'remove' => [
                'label' => 'Quitar filtro',
            ],
            'remove_all' => [
                'label' => 'Quitar todos los filtros',
                'tooltip' => 'Quitar todos los filtros',
            ],
            'reset' => [
                'label' => 'Restablecer',
            ],
        ],
        'heading' => 'Filtros',
        'indicator' => 'Filtros activos',
        'multi_select' => [
            'placeholder' => 'Todo',
        ],
        'select' => [
            'placeholder' => 'Todo',
        ],
        'trashed' => [
            'label' => 'Registros eliminados',
            'only_trashed' => 'Solo registros eliminados',
            'with_trashed' => 'Con registros eliminados',
            'without_trashed' => 'Sin registros eliminados',
        ],
    ],
    'grouping' => [
        'fields' => [
            'group' => [
                'label' => 'Agrupar por',
                'placeholder' => 'Agrupar por',
            ],
            'direction' => [
                'label' => 'Dirección del grupo',
                'options' => [
                    'asc' => 'Ascendente',
                    'desc' => 'Descendente',
                ],
            ],
        ],
    ],
    'reorder_indicator' => 'Arrastra y suelta los registros en orden.',
    'pagination' => [
        'label' => 'Navegación de paginación',
        'overview' => 'Mostrando :first a :last de :total resultados',
        'fields' => [
            'records_per_page' => [
                'label' => 'por página',
                'options' => [
                    'all' => 'Todo',
                ],
            ],
        ],
        'actions' => [
            'first' => [
                'label' => 'Ir a la primera página',
            ],
            'go_to_page' => [
                'label' => 'Ir a la página :page',
            ],
            'last' => [
                'label' => 'Ir a la última página',
            ],
            'next' => [
                'label' => 'Ir a la página siguiente',
            ],
            'previous' => [
                'label' => 'Ir a la página anterior',
            ],
        ],
    ],
    'search' => [
        'label' => 'Buscar',
        'placeholder' => 'Buscar',
        'indicator' => 'Buscar',
    ],
    'sorting' => [
        'fields' => [
            'column' => [
                'label' => 'Ordenar por',
            ],
            'direction' => [
                'label' => 'Dirección del orden',
                'options' => [
                    'asc' => 'Ascendente',
                    'desc' => 'Descendente',
                ],
            ],
        ],
    ],
];