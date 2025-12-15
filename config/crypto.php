<?php

return [
    'patterns' => [
        'ETH'        => '/^0x[a-fA-F0-9]{40}$/',
        'USDT-ERC20' => '/^0x[a-fA-F0-9]{40}$/',
        'BTC'        => '/^(bc1|[13])[a-zA-HJ-NP-Z0-9]{25,39}$/',
        'LTC'        => '/^[LM3][a-km-zA-HJ-NP-Z1-9]{26,33}$/',
        'XRP'        => '/^r[0-9a-zA-Z]{24,34}$/',
        // add more coins as needed
    ],
];