<?php
/**
 * Simple script to rebuild Satis when hit with a HTTP request.
 *
 * @copyright 2017 littleman.co
 * @author    Andrew Howden <hello@andrewhowden.com>
 * @license   MIT
 */

use Monolog\Logger;
use Monolog\Handler\ErrorLogHandler;
use \Monolog\Formatter\JsonFormatter;
use \Monolog\Handler\StreamHandler;

// Todo: Think about making this an application with a routing stack etc. Can then include health checks to ensure
// all things are inspected (2.1)
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

// Set up logging
// Todo: One this is an application, move this into a more general bootstrap. (2.0)
require_once DIR_APP_ROOT . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$oLog          = new Logger('*');
$oLogFormatter = new JsonFormatter();
$oLogHandler   = new ErrorLogHandler();

$oLogHandler->setFormatter($oLogFormatter);
$oLog->pushHandler($oLogHandler);

$command = DIR_BIN . DIRECTORY_SEPARATOR . 'satis';
exec($command, $output, $exitCode);

switch ($exitCode) {
    case 0:
        http_response_code(HTTP_STATUS_OK);
        $oLog->addInfo('Satis successfully rebuilt.');
        echo "OK";
        break;
    default:
        http_response_code(HTTP_STATUS_INTERNAL_SERVER_ERROR);
        echo "NOT OK";
        $oLog->addInfo(sprintf('Satis rebuild failed. Exit code "%s", output "%s"', $exitCode, $output));
        break;
}
