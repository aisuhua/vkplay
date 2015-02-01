<?php


/** Read bootstrap */
$application = require __DIR__ . '/../app/config/bootstrap.php';

/** Handle the request */
echo $application->handle()->getContent();