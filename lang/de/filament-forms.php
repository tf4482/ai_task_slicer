<?php

return [

    'fields' => [

        'code_editor' => [

            'actions' => [

                'copy_to_clipboard' => [
                    'label' => 'In Zwischenablage kopieren',
                ],

            ],

        ],

        'date_time_picker' => [

            'actions' => [

                'clear' => [
                    'label' => 'Löschen',
                ],

                'now' => [
                    'label' => 'Jetzt',
                ],

                'select_date' => [
                    'label' => 'Datum auswählen',
                ],

                'select_time' => [
                    'label' => 'Zeit auswählen',
                ],

            ],

        ],

        'file_upload' => [

            'editor' => [

                'actions' => [

                    'cancel' => [
                        'label' => 'Abbrechen',
                    ],

                    'drag_crop' => [
                        'label' => 'Ziehen Sie den Modus "Zuschneiden"',
                    ],

                    'drag_move' => [
                        'label' => 'Ziehen Sie den Modus "Verschieben"',
                    ],

                    'flip_horizontal' => [
                        'label' => 'Bild horizontal spiegeln',
                    ],

                    'flip_vertical' => [
                        'label' => 'Bild vertikal spiegeln',
                    ],

                    'move_down' => [
                        'label' => 'Bild nach unten verschieben',
                    ],

                    'move_left' => [
                        'label' => 'Bild nach links verschieben',
                    ],

                    'move_right' => [
                        'label' => 'Bild nach rechts verschieben',
                    ],

                    'move_up' => [
                        'label' => 'Bild nach oben verschieben',
                    ],

                    'reset' => [
                        'label' => 'Zurücksetzen',
                    ],

                    'rotate_left' => [
                        'label' => 'Bild nach links drehen',
                    ],

                    'rotate_right' => [
                        'label' => 'Bild nach rechts drehen',
                    ],

                    'save' => [
                        'label' => 'Speichern',
                    ],

                    'zoom_100' => [
                        'label' => 'Bild auf 100% zoomen',
                    ],

                    'zoom_in' => [
                        'label' => 'Hineinzoomen',
                    ],

                    'zoom_out' => [
                        'label' => 'Herauszoomen',
                    ],

                ],

                'fields' => [

                    'height' => [
                        'label' => 'Höhe',
                        'unit' => 'px',
                    ],

                    'rotation' => [
                        'label' => 'Drehung',
                        'unit' => 'deg',
                    ],

                    'width' => [
                        'label' => 'Breite',
                        'unit' => 'px',
                    ],

                    'x_position' => [
                        'label' => 'X',
                        'unit' => 'px',
                    ],

                    'y_position' => [
                        'label' => 'Y',
                        'unit' => 'px',
                    ],

                ],

                'aspect_ratios' => [

                    'label' => 'Seitenverhältnisse',

                    'no_fixed' => [
                        'label' => 'Frei',
                    ],

                ],

                'svg' => [

                    'messages' => [
                        'confirmation' => 'Die Bearbeitung von SVG-Dateien wird nicht empfohlen, da dies zu Qualitätsverlust beim Skalieren führen kann.\nSind Sie sicher, dass Sie fortfahren möchten?',
                        'disabled' => 'Die Bearbeitung von SVG-Dateien ist deaktiviert, da dies zu Qualitätsverlust beim Skalieren führen kann.',
                    ],

                ],

            ],

            'loading_indicator' => 'Wird hochgeladen...',

            'validation' => [
                'invalid_file_type' => 'Ungültiger Dateityp.',
            ],

        ],

        'key_value' => [

            'actions' => [

                'add' => [
                    'label' => 'Zeile hinzufügen',
                ],

                'delete' => [
                    'label' => 'Zeile löschen',
                ],

                'reorder' => [
                    'label' => 'Zeile neu ordnen',
                ],

            ],

            'fields' => [

                'key' => [
                    'label' => 'Schlüssel',
                ],

                'value' => [
                    'label' => 'Wert',
                ],

            ],

        ],

        'markdown_editor' => [

            'toolbar_buttons' => [
                'attach_files' => 'Dateien anhängen',
                'blockquote' => 'Blockzitat',
                'bold' => 'Fett',
                'bullet_list' => 'Aufzählungsliste',
                'code_block' => 'Codeblock',
                'heading' => 'Überschrift',
                'italic' => 'Kursiv',
                'link' => 'Link',
                'ordered_list' => 'Nummerierte Liste',
                'redo' => 'Wiederholen',
                'strike' => 'Durchgestrichen',
                'table' => 'Tabelle',
                'undo' => 'Rückgängig',
            ],

        ],

        'repeater' => [

            'actions' => [

                'add' => [
                    'label' => 'Zu :label hinzufügen',
                ],

                'add_between' => [
                    'label' => 'Zwischen Elementen einfügen',
                ],

                'delete' => [
                    'label' => 'Löschen',
                ],

                'clone' => [
                    'label' => 'Klonen',
                ],

                'move_down' => [
                    'label' => 'Nach unten verschieben',
                ],

                'move_up' => [
                    'label' => 'Nach oben verschieben',
                ],

                'collapse' => [
                    'label' => 'Einklappen',
                ],

                'expand' => [
                    'label' => 'Ausklappen',
                ],

                'collapse_all' => [
                    'label' => 'Alle einklappen',
                ],

                'expand_all' => [
                    'label' => 'Alle ausklappen',
                ],

            ],

        ],

        'rich_editor' => [

            'dialogs' => [

                'link' => [

                    'actions' => [
                        'link' => 'Link',
                        'unlink' => 'Link entfernen',
                    ],

                    'label' => 'URL',

                    'placeholder' => 'URL eingeben',

                ],

            ],

            'toolbar_buttons' => [
                'attach_files' => 'Dateien anhängen',
                'blockquote' => 'Blockzitat',
                'bold' => 'Fett',
                'bullet_list' => 'Aufzählungsliste',
                'code_block' => 'Codeblock',
                'h1' => 'Titel',
                'h2' => 'Überschrift',
                'h3' => 'Unterüberschrift',
                'italic' => 'Kursiv',
                'link' => 'Link',
                'ordered_list' => 'Nummerierte Liste',
                'redo' => 'Wiederholen',
                'strike' => 'Durchgestrichen',
                'underline' => 'Unterstrichen',
                'undo' => 'Rückgängig',
            ],

        ],

        'select' => [

            'actions' => [

                'create_option' => [

                    'modal' => [

                        'heading' => 'Erstellen',

                        'actions' => [

                            'create' => [
                                'label' => 'Erstellen',
                            ],

                            'create_another' => [
                                'label' => 'Erstellen & weiteres erstellen',
                            ],

                        ],

                    ],

                ],

                'edit_option' => [

                    'modal' => [

                        'heading' => 'Bearbeiten',

                        'actions' => [

                            'save' => [
                                'label' => 'Speichern',
                            ],

                        ],

                    ],

                ],

            ],

            'boolean' => [
                'true' => 'Ja',
                'false' => 'Nein',
            ],

            'loading_message' => 'Wird geladen...',

            'max_items_message' => 'Nur :count können ausgewählt werden.',

            'no_search_results_message' => 'Keine Optionen entsprechen Ihrer Suche.',

            'placeholder' => 'Option auswählen',

            'searching_message' => 'Suche...',

            'search_prompt' => 'Beginnen Sie mit der Eingabe, um zu suchen...',

        ],

        'tags_input' => [
            'placeholder' => 'Neues Tag',
        ],

        'text_input' => [

            'actions' => [

                'hide_password' => [
                    'label' => 'Passwort verbergen',
                ],

                'show_password' => [
                    'label' => 'Passwort anzeigen',
                ],

            ],

        ],

        'toggle' => [

            'boolean' => [
                'true' => 'Ja',
                'false' => 'Nein',
            ],

        ],

        'wizard' => [

            'actions' => [

                'previous_step' => [
                    'label' => 'Zurück',
                ],

                'next_step' => [
                    'label' => 'Weiter',
                ],

            ],

        ],

    ],

    'components' => [

        'actions' => [

            'actions' => [
                'label' => 'Aktionen',
            ],

        ],

        'avatar' => [
            'alt' => 'Avatar',
        ],

        'button' => [

            'messages' => [
                'confirm' => 'Sind Sie sicher?',
            ],

        ],

        'color_picker' => [

            'actions' => [

                'open' => [
                    'label' => 'Öffnen',
                ],

            ],

        ],

        'key_value' => [

            'actions' => [

                'add' => [
                    'label' => 'Zeile hinzufügen',
                ],

                'delete' => [
                    'label' => 'Zeile löschen',
                ],

                'reorder' => [
                    'label' => 'Zeile neu ordnen',
                ],

            ],

            'fields' => [

                'key' => [
                    'label' => 'Schlüssel',
                ],

                'value' => [
                    'label' => 'Wert',
                ],

            ],

        ],

        'tabs' => [

            'actions' => [

                'previous_tab' => [
                    'label' => 'Vorheriger Tab',
                ],

                'next_tab' => [
                    'label' => 'Nächster Tab',
                ],

            ],

        ],

    ],

];
