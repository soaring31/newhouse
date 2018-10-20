<?php
if(PHP_VERSION_ID > 70100) {
    require_once __DIR__.'/core/CommonBase71.php';
} elseif(PHP_VERSION_ID > 50600) {
    require_once __DIR__.'/core/CommonBase56.php';
} elseif(PHP_VERSION_ID > 50500) {
    require_once __DIR__.'/core/CommonBase55.php';
} elseif(PHP_VERSION_ID > 50400) {
    require_once __DIR__.'/core/CommonBase54.php';
} else {
    require_once __DIR__.'/core/CommonBase53.php';
}