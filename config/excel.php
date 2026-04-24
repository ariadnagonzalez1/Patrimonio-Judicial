<?php

return [
    'exports' => [
        'chunk_size' => 1000,
        'temp_path' => storage_path('framework/laravel-excel'),
        'csv' => [
            'delimiter' => ',',
            'enclosure' => '"',
            'line_ending' => "\n",
            'use_bom' => false,
            'include_separator_line' => false,
            'excel_compatibility' => false,
            'output_encoding' => '',
        ],
        'xlsx' => [
            'use_inline_strings' => false,
            'disk' => null,
            // 🔹 Evita usar ZipArchive (que necesita exec)
            'writer' => \Maatwebsite\Excel\Excel::XLSX,
        ],
    ],

    'imports' => [
        'read_only' => true,
        'heading_row' => [
            'formatter' => 'slug',
        ],
    ],

    'extension_detector' => [
        'xlsx' => 'Xlsx',
        'xls' => 'Xls',
        'csv' => 'Csv',
        'ods' => 'Ods',
    ],
];
