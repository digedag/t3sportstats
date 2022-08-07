<?php

defined('TYPO3_MODE') or exit();

call_user_func(function () {
    $extKey = 't3sportstats';

    // list static templates in templates selection
    \Sys25\RnBase\Utility\Extensions::addStaticFile($extKey, 'Configuration/TypoScript/Plugin/', 'T3sportstats');
});
