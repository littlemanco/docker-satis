<?php
/**
 * Simple script to rebuild Satis when hit with a HTTP request.
 *
 * @copyright 2017 littleman.co
 * @author    Andrew Howden <hello@andrewhowden.com>
 * @license   MIT
 */

// Todo: Think about making this an application with a routing stack etc. Can then include health checks to ensure
// all things are spected.
//
// Health checks would include
// - Is satis.json there
// - Is directory writeable
// - Is satis.json populated
// And other such sanity checking.

const DIR_APP_ROOT = __DIR__ . DIRECTORY_SEPARATOR . '..';
const DIR_BIN      = DIR_APP_ROOT . DIRECTORY_SEPARATOR . 'bin';

const HTTP_STATUS_OK                    = 200;
const HTTP_STATUS_BAD_REQUEST           = 400;
const HTTP_STATUS_INTERNAL_SERVER_ERROR = 500;

$command = DIR_BIN . DIRECTORY_SEPARATOR . 'satis';

exec($command, $output, $returnVar);

switch ($returnVar) {
    case 0:
        http_send_status(HTTP_STATUS_OK);
        echo "OK";
        break;
    default:
        http_send_status(HTTP_STATUS_INTERNAL_SERVER_ERROR);
        echo "NOT OK";
        break;
}
