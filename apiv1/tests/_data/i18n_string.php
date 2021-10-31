<?php
return array_merge(
    require dirname(__DIR__, 3)
        . '/common/tests/_data/i18n_string.php', [
    [
        'id' => 100,
        'default' => 'area-1-name',
        'en' => 'area-1-name',
    ],
    [
        'id' => 101,
        'default' => 'area-2-name',
        'en' => 'area-2-name',
    ],
]);
