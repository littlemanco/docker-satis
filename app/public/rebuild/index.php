<?php
/**
 * Simple script to rebuild Satis when hit with a HTTP request.
 *
 * Currently, it does nothing, but in future it will.
 *
 * @copyright 2017 littleman.co
 * @author    Andrew Howden <hello@andrewhowden.com>
 * @license   MIT
 */

const HTTP_OK = 200;

http_response_code(HTTP_OK);

echo "OK";
