<?php

/**
 * EasySpinRdf
 *
 * LICENSE
 *
 * Copyright (c) 2009-2013 Nicholas J Humfrey.  All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright notice,
 *    this list of conditions and the following disclaimer in the documentation
 *    and/or other materials provided with the distribution.
 * 3. The name of the author 'Nicholas J Humfrey" may be used to endorse or
 *    promote products derived from this software without specific prior
 *    written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package    EasySpinRdf
 * @copyright  Conjecto - Blaise de Carné
 * @license    http://www.opensource.org/licenses/bsd-license.php
 */

/*
 * Include PHPUnit dependencies
 */
require_once 'PHPUnit/Framework/IncompleteTestError.php';
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/Runner/Version.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'PHPUnit/Util/Filter.php';

/*
 * Set error reporting to the level to be stricter.
 */
error_reporting(E_ALL | E_STRICT);

/*
 * Check the version number of PHP Unit.
 */
if (version_compare(PHPUnit_Runner_Version::id(), '3.5.15', '<')) {
    error_log("PHPUnit version 3.5.15 or higher is required.");
    exit();
}

// Set time zone to UTC for running tests
date_default_timezone_set('UTC');

require_once dirname(dirname(__FILE__)).'/vendor/autoload.php';
require_once 'EasySpinRdf/TestCase.php';
EasyRdf_Namespace::set('test', 'http://www.example.org/test-easyspinrdf#');
EasySpinRdf_Utils::setTypeMappers();

/**
 * Helper function: get path to a fixture file
 *
 * @param string $name fixture file name
 * @return string Path to the fixture file
 */
function fixturePath($name)
{
    return dirname(__FILE__) . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . $name;
}

/**
 * Helper function: read fixture data from file
 *
 * @param string $name fixture file name
 * @return string Fixture data
 */
function readFixture($name)
{
    return file_get_contents(
        fixturePath($name)
    );
}

/**
 * Helper function: check to see if a required file exists
 *
 * @param string $filename the filename to check
 * @return boolean Returns true if the file exists
 */
function requireExists($filename)
{
    $paths = explode(PATH_SEPARATOR, get_include_path());
    foreach ($paths as $path) {
        if (substr($path, -1) == DIRECTORY_SEPARATOR) {
            $fullpath = $path.$filename;
        } else {
            $fullpath = $path.DIRECTORY_SEPARATOR.$filename;
        }
        if (file_exists($fullpath)) {
            return true;
        }
    }

    return false;
}
