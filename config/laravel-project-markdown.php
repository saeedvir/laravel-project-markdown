<?php

return [
    /*
    |--------------------------------------------------------------------------
    | File Documentation Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for the project:files-markdown command.
    |
    */
    'files' => [
        /*
        |--------------------------------------------------------------------------
        | Default Exclude Patterns
        |--------------------------------------------------------------------------
        |
        | Directories to exclude from file analysis by default.
        | These patterns are used to skip unnecessary directories during analysis.
        |
        */
        'exclude_directories' => [
            'vendor',
            'storage',
            'node_modules',
            'tests',
            '.git',
            'build',
            'dist',
            'coverage',
            '.idea',
            '.vscode',
            'bootstrap/cache',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Database Documentation Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for the project:db-markdown command.
    |
    */
    'database' => [

        /*
        |--------------------------------------------------------------------------
        | ER Diagram Settings
        |--------------------------------------------------------------------------
        |
        | Settings for ER diagram generation.
        |
        */
        'er_diagram' => [
            'colors' => [
                '#FFDDC1',
                '#FFABAB',
                '#FFC3A0',
                '#D5AAFF',
                '#85E3FF',
                '#B9FBC0',
                '#FFF5BA',
                '#F1C0E8',
            ],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Markdown Template Settings
    |--------------------------------------------------------------------------
    |
    | Settings for customizing the generated markdown output.
    |
    */
    'markdown' => [
        'include_package_info' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | JSON Output Settings
    |--------------------------------------------------------------------------
    |
    | Settings for JSON output generation.
    |
    */
    'json' => [
        'enabled' => true,
    ],
];
