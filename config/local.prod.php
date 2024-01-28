<?php

// Production environment

return function (array $settings): array {
    $settings['db']['database'] = 'slim_skeleton';

    // users.hash salt
    $settings['salt'] = '';

    return $settings;
};
