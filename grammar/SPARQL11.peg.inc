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

use hafriedlander\Peg\Parser;

/**
 * SPARQL Parser to EasySpinRdf_Query
 *
 * @package    EasySpinRdf
 * @copyright  Conjecto - Blaise de Carné
 * @license    http://www.opensource.org/licenses/bsd-license.php
 */
class EasySpinRdf_Parser extends Parser\Basic {

    /*!* SPARQL11

    SelectClause: 'SELECT' [modifier:('DISTINCT'|'REDUCED')]?

    SelectQuery: SelectClause

    */

    /**
     * @var EasyRdf_Graph|null
     */
    private $graph = null;


    /**
     * Construct the parser
     *
     * @param $query
     */
    public function __construct($query)
    {
        EasySpinRdf_Utils::setTypeMappers();
        $this->graph = new EasyRdf_Graph();
        parent::__construct($query);
    }

    /**
     * Return the parsed query
     * @return EasySpinRdf_Query
     */
    public function parse()
    {
        $node = $this->match_SelectQuery();
        return $node['query'];
    }

    /**
     * Create a new SelectQuery
     *
     * @param $result
     */
    public function SelectQuery__construct(&$result)
    {
        $result['query'] = $this->graph->newBNode(array('sp:Select'));
    }

    /**
     * SelectQuery : SelectClause
     * @param $result
     * @param $sub
     */
    public function SelectQuery_SelectClause(&$result, $sub)
    {
        if(isset($sub['modifier'])) {
            switch($sub['modifier']['text']) {
                case "DISTINCT":
                    $result['query']->add('sp:distinct', new EasyRdf_Literal_Boolean(true));
                    break;
                case "REDUCED":
                    $result['query']->add('sp:reduced', new EasyRdf_Literal_Boolean(true));
                    break;
            }
        }
    }
}
