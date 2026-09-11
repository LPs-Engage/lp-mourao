<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Tracking (GTM, Hotjar, Meta Pixel)
    |--------------------------------------------------------------------------
    |
    | IDs lidos em runtime pelo Laravel e enviados ao front via Inertia.
    | Assim o deploy funciona só com .env — sem depender de rebuild do Vite.
    |
    | Aceita nomes sem prefixo (recomendado) e VITE_* (compatibilidade).
    |
    */

    'gtm_id' => env('GTM_ID', env('VITE_GTM_ID', '')),

    'gtm_load_strategy' => env('GTM_LOAD_STRATEGY', env('VITE_GTM_LOAD_STRATEGY', 'delayed')),

    'hotjar_id' => env('HOTJAR_ID', env('VITE_HOTJAR_ID', '')),

    'hotjar_load_strategy' => env('HOTJAR_LOAD_STRATEGY', env('VITE_HOTJAR_LOAD_STRATEGY', 'idle')),

    'meta_pixel_id' => env('META_PIXEL_ID', env('VITE_META_PIXEL_ID', '')),

    'meta_pixel_load_strategy' => env('META_PIXEL_LOAD_STRATEGY', env('VITE_META_PIXEL_LOAD_STRATEGY', 'delayed')),

];
