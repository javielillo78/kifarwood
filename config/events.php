<?php

return [
    // Desactiva el event discovery globalmente
    'discover' => false,

    // (Opcional) Config por defecto si un provider lo usa:
    'discovery' => [
        'within' => [app_path()],
        'exclude' => [],
    ],
];
