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

class EasySpinRdf_Query_ParserTest extends EasySpinRdf_TestCase
{
    public function testParseSelectDistinctQuery()
    {
        $sparql = "SELECT DISTINCT ?this WHERE { ?this ?predicate ?object }";
        $parser = new EasySpinRdf_Parser($sparql);
        $query = $parser->parse();

        $this->assertClass('EasySpinRdf_Query_Select', $query);
        $this->assertEquals(true, $query->getDistinct());

        $this->assertNotNull($query->get('sp:resultVariables'));
        $this->assertClass('EasyRdf_Collection', $query->get('sp:resultVariables'));

        $this->assertNotNull($query->get('sp:where'));
        $this->assertClass('EasyRdf_Collection', $query->get('sp:where'));

        $this->assertEquals("SELECT DISTINCT ?this WHERE { ?this ?predicate ?object }", $query->getSparql());
    }

    public function testParseSelectAllQuery()
    {
        $sparql = "SELECT * WHERE { ?this ?predicate ?object }";
        $parser = new EasySpinRdf_Parser($sparql);
        $query = $parser->parse();

        $this->assertClass('EasySpinRdf_Query_Select', $query);
        $this->assertNull($query->get('sp:resultVariables'));
        $this->assertNotNull($query->get('sp:where'));
        $this->assertClass('EasyRdf_Collection', $query->get('sp:where'));

        $this->assertEquals("SELECT * WHERE { ?this ?predicate ?object }", $query->getSparql());
    }

    public function testParseSelectMultiVarAndTripleQuery()
    {
        $sparql = "SELECT ?this ?predicate ?object WHERE { ?this ?predicate ?object. ?this ?predicate2 ?object2. ?this ?predicate3 ?object3 }";
        $parser = new EasySpinRdf_Parser($sparql);
        $query = $parser->parse();

        $this->assertClass('EasySpinRdf_Query_Select', $query);


        $this->assertNotNull($query->get('sp:resultVariables'));
        $this->assertClass('EasyRdf_Collection', $query->get('sp:resultVariables'));

        $this->assertNotNull($query->get('sp:where'));
        $this->assertClass('EasyRdf_Collection', $query->get('sp:where'));

        $this->assertEquals("SELECT ?this ?predicate ?object WHERE { ?this ?predicate ?object. ?this ?predicate2 ?object2. ?this ?predicate3 ?object3 }", $query->getSparql());
    }

    public function testParseSelectAndSubSelectQuery()
    {
        $sparql = "SELECT * WHERE { { SELECT ?object WHERE { ?this2 ?predicate2 ?object } } }";
        $parser = new EasySpinRdf_Parser($sparql);
        $query = $parser->parse();

        $this->assertClass('EasySpinRdf_Query_Select', $query);

        $this->assertNull($query->get('sp:resultVariables'));

        $this->assertNotNull($query->get('sp:where'));
        $this->assertClass('EasyRdf_Collection', $query->get('sp:where'));

        $this->assertEquals("SELECT * WHERE { { SELECT ?object WHERE { ?this2 ?predicate2 ?object } } }", $query->getSparql());
    }

    public function testParseSelectOrderByVarQuery()
    {
        $sparql = "SELECT * WHERE { ?this ?predicate ?object } ORDER BY ?predicate";
        $parser = new EasySpinRdf_Parser($sparql);
        $query = $parser->parse();

        $this->assertClass('EasySpinRdf_Query_Select', $query);

        $this->assertNull($query->get('sp:resultVariables'));

        $this->assertNotNull($query->get('sp:where'));
        $this->assertClass('EasyRdf_Collection', $query->get('sp:where'));
        $this->assertNotNull($query->get('sp:orderBy'));
        $this->assertClass('EasyRdf_Collection', $query->get('sp:orderBy'));

        $this->assertEquals("SELECT * WHERE { ?this ?predicate ?object } ORDER BY ?predicate", $query->getSparql());
    }

    public function testParseSelectOrderByASCDESCQuery()
    {
        $sparql = "SELECT * WHERE { ?this ?predicate ?object } ORDER BY ASC ( ?predicate ) DESC ( ?object )";
        $parser = new EasySpinRdf_Parser($sparql);
        $query = $parser->parse();

        $this->assertClass('EasySpinRdf_Query_Select', $query);

        $this->assertNull($query->get('sp:resultVariables'));

        $this->assertNotNull($query->get('sp:where'));
        $this->assertClass('EasyRdf_Collection', $query->get('sp:where'));
        $this->assertNotNull($query->get('sp:orderBy'));
        $this->assertClass('EasyRdf_Collection', $query->get('sp:orderBy'));

        $this->assertEquals("SELECT * WHERE { ?this ?predicate ?object } ORDER BY ASC(?predicate) DESC(?object)", $query->getSparql());
    }

    public function testParseSelectLimitQuery()
    {
        $sparql = "SELECT * WHERE { ?this ?predicate ?object } LIMIT 10";
        $parser = new EasySpinRdf_Parser($sparql);
        $query = $parser->parse();

        $this->assertClass('EasySpinRdf_Query_Select', $query);

        $this->assertNull($query->get('sp:resultVariables'));

        $this->assertNotNull($query->get('sp:where'));
        $this->assertClass('EasyRdf_Collection', $query->get('sp:where'));
        $this->assertNotNull($query->get('sp:limit'));

        $this->assertEquals("SELECT * WHERE { ?this ?predicate ?object } LIMIT 10", $query->getSparql());
    }

    public function testParseSelectOffsetQuery()
    {
        $sparql = "SELECT * WHERE { ?this ?predicate ?object } OFFSET 10";
        $parser = new EasySpinRdf_Parser($sparql);
        $query = $parser->parse();

        $this->assertClass('EasySpinRdf_Query_Select', $query);

        $this->assertNull($query->get('sp:resultVariables'));

        $this->assertNotNull($query->get('sp:where'));
        $this->assertClass('EasyRdf_Collection', $query->get('sp:where'));
        $this->assertNotNull($query->get('sp:offset'));

        $this->assertEquals("SELECT * WHERE { ?this ?predicate ?object } OFFSET 10", $query->getSparql());
    }

    public function testParseSelectLimitAndOffsetQuery()
    {
        $sparql = "SELECT * WHERE { ?this ?predicate ?object } LIMIT 10 OFFSET 5";
        $parser = new EasySpinRdf_Parser($sparql);
        $query = $parser->parse();

        $this->assertClass('EasySpinRdf_Query_Select', $query);

        $this->assertNull($query->get('sp:resultVariables'));

        $this->assertNotNull($query->get('sp:where'));
        $this->assertClass('EasyRdf_Collection', $query->get('sp:where'));
        $this->assertNotNull($query->get('sp:limit'));
        $this->assertNotNull($query->get('sp:offset'));

        $this->assertEquals("SELECT * WHERE { ?this ?predicate ?object } LIMIT 10 OFFSET 5", $query->getSparql());
    }
}
