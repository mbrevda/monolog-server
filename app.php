#!/usr/bin/env php
<?php

if (php_sapi_name() == "cli") {
    require __DIR__ . '/cli/console.php';
}
