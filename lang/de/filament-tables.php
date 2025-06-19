<?php

return [

    'columns' => [

        'select_all' => [
            'label' => 'Alle auswählen',
        ],

        'text' => [

            'actions' => [
                'collapse_list' => 'Weniger anzeigen',
                'expand_list' => 'Mehr anzeigen',
            ],

            'more_list_items' => 'und :count weitere',

        ],

    ],

    'fields' => [

        'bulk_select_page' => [
            'label' => 'Alle Elemente für Massenaktionen auswählen/abwählen.',
        ],

        'bulk_select_record' => [
            'label' => 'Element :key für Massenaktionen auswählen/abwählen.',
        ],

        'bulk_select_group' => [
            'label' => 'Gruppe :title für Massenaktionen auswählen/abwählen.',
        ],

        'search' => [
            'label' => 'Suchen',
            'placeholder' => 'Suchen',
            'indicator' => 'Suchen',
        ],

    ],

    'summary' => [

        'heading' => 'Zusammenfassung',

        'subheadings' => [
            'all' => 'Alle :label',
            'group' => ':group Zusammenfassung',
            'page' => 'Diese Seite',
        ],

        'summarizers' => [

            'average' => [
                'label' => 'Durchschnitt',
            ],

            'count' => [
                'label' => 'Anzahl',
            ],

            'sum' => [
                'label' => 'Summe',
            ],

        ],

    ],

    'actions' => [

        'disable_reordering' => [
            'label' => 'Neuordnung der Datensätze beenden',
        ],

        'enable_reordering' => [
            'label' => 'Datensätze neu ordnen',
        ],

        'filter' => [
            'label' => 'Filter',
        ],

        'group' => [
            'label' => 'Gruppieren',
        ],

        'open_bulk_actions' => [
            'label' => 'Massenaktionen öffnen',
        ],

        'toggle_columns' => [
            'label' => 'Spalten umschalten',
        ],

    ],

    'empty' => [

        'heading' => 'Keine :model gefunden',

        'description' => 'Erstellen Sie eine :model, um zu beginnen.',

    ],

    'filters' => [

        'actions' => [

            'apply' => [
                'label' => 'Filter anwenden',
            ],

            'remove' => [
                'label' => 'Filter entfernen',
            ],

            'remove_all' => [
                'label' => 'Alle Filter entfernen',
            ],

            'reset' => [
                'label' => 'Zurücksetzen',
            ],

        ],

        'heading' => 'Filter',

        'indicator' => 'Aktive Filter',

        'multi_select' => [
            'placeholder' => 'Alle',
        ],

        'select' => [
            'placeholder' => 'Alle',
        ],

        'trinary' => [

            'placeholder' => 'Alle',

            'true_label' => 'Ja',

            'false_label' => 'Nein',

        ],

    ],

    'grouping' => [

        'fields' => [

            'group' => [
                'label' => 'Gruppieren nach',
                'placeholder' => 'Gruppieren nach',
            ],

            'direction' => [

                'label' => 'Gruppierungsrichtung',

                'options' => [
                    'asc' => 'Aufsteigend',
                    'desc' => 'Absteigend',
                ],

            ],

        ],

    ],

    'reorder_indicator' => 'Ziehen Sie die Datensätze in die gewünschte Reihenfolge.',

    'selection_indicator' => [

        'selected_count' => '1 Datensatz ausgewählt|:count Datensätze ausgewählt',

        'actions' => [

            'select_all' => [
                'label' => 'Alle :count auswählen',
            ],

            'deselect_all' => [
                'label' => 'Alle abwählen',
            ],

        ],

    ],

    'sorting' => [

        'fields' => [

            'column' => [
                'label' => 'Sortieren nach',
            ],

            'direction' => [

                'label' => 'Sortierrichtung',

                'options' => [
                    'asc' => 'Aufsteigend',
                    'desc' => 'Absteigend',
                ],

            ],

        ],

    ],

];
