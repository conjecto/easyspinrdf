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

require_once dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'TestHelper.php';

class EasySpinRdf_Expression_AggregationTest extends EasySpinRdf_TestCase
{
    var $graph;

    public function setUp()
    {
        $this->graph = new EasyRdf_Graph();
        $this->graph->parse(readFixture('expression/aggregation.ttl'), 'turtle');
    }

    public function testAvg()
    {
        $query = $this->graph->resource('test:avg');
        $this->assertClass('EasySpinRdf_Query_Select', $query);
        $this->assertStringEquals("SELECT AVG(?object) WHERE { ?this ?arg1 ?object }", $query->getSparql());
    }

    public function testCount()
    {
        $query = $this->graph->resource('test:count');
        $this->assertClass('EasySpinRdf_Query_Select', $query);
        $this->assertStringEquals("SELECT COUNT(?object) WHERE { ?this ?arg1 ?object }", $query->getSparql());
    }

    public function testMax()
    {
        $query = $this->graph->resource('test:max');
        $this->assertClass('EasySpinRdf_Query_Select', $query);
        $this->assertStringEquals("SELECT MAX(?object) WHERE { ?this ?arg1 ?object }", $query->getSparql());
    }

    public function testMin()
    {
        $query = $this->graph->resource('test:min');
        $this->assertClass('EasySpinRdf_Query_Select', $query);
        $this->assertStringEquals("SELECT MIN(?object) WHERE { ?this ?arg1 ?object }", $query->getSparql());
    }

    public function testSum()
    {
        $query = $this->graph->resource('test:sum');
        $this->assertClass('EasySpinRdf_Query_Select', $query);
        $this->assertStringEquals("SELECT SUM(?object) WHERE { ?this ?arg1 ?object }", $query->getSparql());
    }
}
