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

    /* SelectClause: 'SELECT' [modifier:('DISTINCT'|'REDUCED')]? */
    protected $match_SelectClause_typestack = array('SelectClause');
    function match_SelectClause ($stack = array()) {
    	$matchrule = "SelectClause"; $result = $this->construct($matchrule, $matchrule, null);
    	$_10 = NULL;
    	do {
    		if (( $subres = $this->literal( 'SELECT' ) ) !== FALSE) { $result["text"] .= $subres; }
    		else { $_10 = FALSE; break; }
    		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
    		else { $_10 = FALSE; break; }
    		$stack[] = $result; $result = $this->construct( $matchrule, "modifier" ); 
    		$_7 = NULL;
    		do {
    			$_5 = NULL;
    			do {
    				$res_2 = $result;
    				$pos_2 = $this->pos;
    				if (( $subres = $this->literal( 'DISTINCT' ) ) !== FALSE) {
    					$result["text"] .= $subres;
    					$_5 = TRUE; break;
    				}
    				$result = $res_2;
    				$this->pos = $pos_2;
    				if (( $subres = $this->literal( 'REDUCED' ) ) !== FALSE) {
    					$result["text"] .= $subres;
    					$_5 = TRUE; break;
    				}
    				$result = $res_2;
    				$this->pos = $pos_2;
    				$_5 = FALSE; break;
    			}
    			while(0);
    			if( $_5 === FALSE) { $_7 = FALSE; break; }
    			$_7 = TRUE; break;
    		}
    		while(0);
    		if( $_7 === TRUE ) {
    			$subres = $result; $result = array_pop($stack);
    			$this->store( $result, $subres, 'modifier' );
    		}
    		if( $_7 === FALSE) {
    			$result = array_pop($stack);
    			$_10 = FALSE; break;
    		}
    		$res_9 = $result;
    		$pos_9 = $this->pos;
    		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
    		else {
    			$result = $res_9;
    			$this->pos = $pos_9;
    			unset( $res_9 );
    			unset( $pos_9 );
    		}
    		$_10 = TRUE; break;
    	}
    	while(0);
    	if( $_10 === TRUE ) { return $this->finalise($result); }
    	if( $_10 === FALSE) { return FALSE; }
    }


    /* SelectQuery: SelectClause */
    protected $match_SelectQuery_typestack = array('SelectQuery');
    function match_SelectQuery ($stack = array()) {
    	$matchrule = "SelectQuery"; $result = $this->construct($matchrule, $matchrule, null);
    	$matcher = 'match_'.'SelectClause'; $key = $matcher; $pos = $this->pos;
    	$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
    	if ($subres !== FALSE) {
    		$this->store( $result, $subres );
    		return $this->finalise($result);
    	}
    	else { return FALSE; }
    }




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
            if($sub['modifier']['text'] == "DISTINCT") {
                $result['query']->add('sp:distinct', new EasyRdf_Literal_Boolean(true));
            }
        }
    }
}
