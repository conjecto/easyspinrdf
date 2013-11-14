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

class EasySpinRdf_Query_SelectTest extends EasySpinRdf_TestCase
{
    private $graph;

    public function setUp()
    {
        $this->graph = new EasyRdf_Graph();
        $this->graph->parse(readFixture('query/select.ttl'), 'turtle');
    }

    public function testSelect()
    {
        $query = $this->graph->resource('test:select');
        $this->assertClass('EasySpinRdf_Query_Select', $query);
        $this->assertStringEquals("SELECT * WHERE { ?this test:predicate ?object }", $query->getSparql());
    }

    public function testVariables()
    {
        $query = $this->graph->resource('test:variables');
        $this->assertClass('EasySpinRdf_Query_Select', $query);
        $this->assertStringEquals("SELECT ?this ?object WHERE { ?this test:predicate ?object }", $query->getSparql());
    }

    public function testDistinct()
    {
        $query = $this->graph->resource('test:distinct');
        $this->assertClass('EasySpinRdf_Query_Select', $query);
        $this->assertEquals(true, $query->getDistinct());
        $this->assertStringEquals("SELECT DISTINCT ?this WHERE { ?this test:predicate ?object }", $query->getSparql());
    }

    public function testReduced()
    {
        $query = $this->graph->resource('test:reduced');
        $this->assertClass('EasySpinRdf_Query_Select', $query);
        $this->assertEquals(true, $query->getReduced());
        $this->assertStringEquals("SELECT REDUCED ?this WHERE { ?this test:predicate ?object }", $query->getSparql());
    }

    public function testLimitOffset()
    {
        $query = $this->graph->resource('test:limitOffset');
        $this->assertClass('EasySpinRdf_Query_Select', $query);
        $this->assertStringEquals("SELECT ?this WHERE { ?this test:predicate ?object } LIMIT 5 OFFSET 10", $query->getSparql());
    }

    public function testOrderBy()
    {
        $query = $this->graph->resource('test:orderBy');
        $this->assertClass('EasySpinRdf_Query_Select', $query);
        $this->assertStringEquals("SELECT * WHERE { ?this test:predicate ?value } ORDER BY ?value", $query->getSparql());

        $query = $this->graph->resource('test:orderByExpression');
        $this->assertClass('EasySpinRdf_Query_Select', $query);
        $this->assertStringEquals("SELECT * WHERE { ?this test:predicate ?value } ORDER BY MAX(?value)", $query->getSparql());

        $query = $this->graph->resource('test:orderByAscDesc');
        $this->assertClass('EasySpinRdf_Query_Select', $query);
        $this->assertStringEquals("SELECT * WHERE { ?this test:predicate ?value } ORDER BY ASC(?value) DESC(?this)", $query->getSparql());
    }
}
