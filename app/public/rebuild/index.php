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

// Todo: Think about making this an application with a routing stack etc. Can then include health checks to ensure
// all things are inspected (v2.1)
//
// Health checks would include
// - Is satis.json there
// - Is directory writeable
// - Is satis.json populated
// And other such sanity checking.

const DIR_APP_ROOT = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..';
const DIR_BIN      = DIR_APP_ROOT . DIRECTORY_SEPARATOR . 'bin';

const HTTP_STATUS_OK                    = 200;
const HTTP_STATUS_BAD_REQUEST           = 400;
const HTTP_STATUS_INTERNAL_SERVER_ERROR = 500;

// Set up logging
// Todo: One this is an application, move this into a more general bootstrap. (v2.0)
require_once DIR_APP_ROOT . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$oLog          = new Logger('*');
$oLogFormatter = new JsonFormatter();
$oLogHandler   = new ErrorLogHandler();

// Todo: Modify this to use the standard log format
// (v1.1)
$oLogHandler->setFormatter($oLogFormatter);
$oLog->pushHandler($oLogHandler);

// Acquire lock
// Todo: Ensure that if something fails, the lock still gets released or times out. (v3.0)
$sLockFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'satis.lock';
touch($sLockFile);

$rLockFile = fopen($sLockFile, 'r+');
$bLock = flock($rLockFile, LOCK_EX | LOCK_NB);
if ($bLock === false) {
    http_response_code(HTTP_STATUS_INTERNAL_SERVER_ERROR);
    echo "NOT OK";
    $oLog->addError(sprintf('Unable to acquire lock on file "%s"', $sLockFile));
    exit();
} else {
    $oLog->addDebug(sprintf('Successfully acquired lock on file "%s"', $sLockFile));
}

$command = sprintf(
    '%s build /etc/satis/satis.json public',
    DIR_BIN . DIRECTORY_SEPARATOR . 'satis'
);

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
        $oLog->addInfo(sprintf('Satis rebuild failed. Exit code "%s", output "%s"', $exitCode, implode("\n", $output)));
        break;
}

// Release the lock
flock($rLockFile, LOCK_UN);
fclose($rLockFile);
unlink($sLockFile);
