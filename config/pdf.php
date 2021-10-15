<?php

return [
    'mode'                     => '',
    'format'                   => 'A2-L',
    'default_font_size'        => '14',
    'default_font'             => 'sans-serif',
    'margin_left'              => 10,
    'margin_right'             => 10,
    'margin_top'               => 10,
    'margin_bottom'            => 10,
    'margin_header'            => 0,
    'margin_footer'            => 0,
    'orientation'              => 'P',
    'title'                    => 'التقرير الشهري للمتابعة - مركز الفرقان لتعليم القراّن',
    'author'                   => '',
    'watermark'                => '',
    'show_watermark'           => false,
    'watermark_font'           => 'sans-serif',
    'display_mode'             => 'fullpage',
    'watermark_text_alpha'     => 0.1,
    'custom_font_dir' => base_path('resources/fonts/'), // don't forget the trailing slash!
    'custom_font_data' => [
        'arial' => [
            'R'  => 'alfont_com_arial-1.ttf',    // regular font
            'B'  => 'alfont_com_AlFont_com_arialbd.ttf',       // optional: bold font
            'I'  => 'alfont_com_arial-1.ttf',     // optional: italic font
            'BI' => 'alfont_com_arial-1.ttf', // optional: bold-italic font
            'useOTL' => 0xFF,
            'useKashida' => 75,
        ]
        // ...add as many as you want.
    ],
    'auto_language_detection'  => false,
    'temp_dir'                 => rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR),
    'pdfa' 			               => false,
    'pdfaauto' 		             => false,
    'use_active_forms'         => false,
];
