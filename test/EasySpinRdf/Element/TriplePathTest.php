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

class EasySpinRdf_Element_TriplePathTest extends EasySpinRdf_TestCase
{
    var $graph;

    public function setUp()
    {
        $this->graph = new EasyRdf_Graph();
        $this->graph->parse(readFixture('element/triple_path.ttl'), 'turtle');
    }

    public function testParseSeqPath()
    {
        $triplePath = $this->graph->resource('test:seqPath');
        $this->assertClass('EasySpinRdf_Element_TriplePath', $triplePath);
        $this->assertClass('EasySpinRdf_Expression_Path_SeqPath', $triplePath->get('sp:path'));
        $this->assertStringEquals("?this foaf:knows/foaf:name ?name", $triplePath->getSparql());
    }

    public function testParseAltPath()
    {
        $triplePath = $this->graph->resource('test:altPath');
        $this->assertClass('EasySpinRdf_Element_TriplePath', $triplePath);
        $this->assertClass('EasySpinRdf_Expression_Path_AltPath', $triplePath->get('sp:path'));
        $this->assertStringEquals("?this foaf:knows|foaf:name ?name", $triplePath->getSparql());
    }

    public function testParseModPath()
    {
        $triplePath = $this->graph->resource('test:modPath1');
        $this->assertClass('EasySpinRdf_Element_TriplePath', $triplePath);
        $this->assertClass('EasySpinRdf_Expression_Path_ModPath', $triplePath->get('sp:path'));
        $this->assertStringEquals("?this foaf:knows* ?person", $triplePath->getSparql());

        $triplePath = $this->graph->resource('test:modPath2');
        $this->assertClass('EasySpinRdf_Element_TriplePath', $triplePath);
        $this->assertClass('EasySpinRdf_Expression_Path_ModPath', $triplePath->get('sp:path'));
        $this->assertStringEquals("?this foaf:knows? ?person", $triplePath->getSparql());

        $triplePath = $this->graph->resource('test:modPath3');
        $this->assertClass('EasySpinRdf_Element_TriplePath', $triplePath);
        $this->assertClass('EasySpinRdf_Expression_Path_ModPath', $triplePath->get('sp:path'));
        $this->assertStringEquals("?this foaf:knows+ ?person", $triplePath->getSparql());

        $triplePath = $this->graph->resource('test:modPath4');
        $this->assertClass('EasySpinRdf_Element_TriplePath', $triplePath);
        $this->assertClass('EasySpinRdf_Expression_Path_ModPath', $triplePath->get('sp:path'));
        $this->assertStringEquals("?this foaf:knows{3,} ?person", $triplePath->getSparql());

        $triplePath = $this->graph->resource('test:modPath5');
        $this->assertClass('EasySpinRdf_Element_TriplePath', $triplePath);
        $this->assertClass('EasySpinRdf_Expression_Path_ModPath', $triplePath->get('sp:path'));
        $this->assertStringEquals("?this foaf:knows{,3} ?person", $triplePath->getSparql());

        $triplePath = $this->graph->resource('test:modPath6');
        $this->assertClass('EasySpinRdf_Element_TriplePath', $triplePath);
        $this->assertClass('EasySpinRdf_Expression_Path_ModPath', $triplePath->get('sp:path'));
        $this->assertStringEquals("?this foaf:knows{2,8} ?person", $triplePath->getSparql());
    }

    public function testParseReversePath()
    {
        $triplePath = $this->graph->resource('test:reversePath');
        $this->assertClass('EasySpinRdf_Element_TriplePath', $triplePath);
        $this->assertClass('EasySpinRdf_Expression_Path_ReversePath', $triplePath->get('sp:path'));
        $this->assertStringEquals("?this ^foaf:knows ?person", $triplePath->getSparql());
    }

    public function testParseComplexPath()
    {
        $triplePath = $this->graph->resource('test:complexPath');
        $this->assertClass('EasySpinRdf_Element_TriplePath', $triplePath);
        $this->assertStringEquals("?this foaf:knows+/foaf:name ?name", $triplePath->getSparql());
    }
}
