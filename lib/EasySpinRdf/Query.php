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

/**
 * Abstract class that represents an SPIN query
 *
 * @package    EasySpinRdf
 * @copyright  Conjecto - Blaise de Carné
 * @license    http://www.opensource.org/licenses/bsd-license.php
 */
abstract class EasySpinRdf_Query extends EasySpinRdf_Resource
{
    /** query keyword */
    const SPARQL_QUERY_KEYWORD = null;

    /**
     * Get the query comment
     * @return string
     */
    public function getComment()
    {
        return $this->get('rdfs:comment');
    }

    /**
     * Get the specific pattern part
     * @return string
     */
    public function getPattern()
    {
        return false;
    }

    /**
     * Get the FROM part
     * @return string
     */
    public function getFrom()
    {
        return $this->getStatements('sp:from');
    }

    /**
     * Get the FROM NAMED part
     * @return string
     */
    public function getFromNamed()
    {
        return $this->getStatements('sp:fromnamed');
    }

    /**
     * Get the WHERE part
     * @return string
     */
    public function getWhere()
    {
        return $this->getStatements('sp:where');
    }

    /**
     * Get the ORDER BY part
     * @return string
     */
    public function getOrderBy()
    {
        $orderBy = $this->get('sp:orderBy');
        if($orderBy) {
            return $this->resourceToSparql($orderBy);
        }
        return null;
    }

    /**
     * Get the LIMIT part
     * @return string
     */
    public function getLimit()
    {
        $limit = $this->get('sp:limit');
        if($limit) {
            return $limit->getValue();
        }
        return null;
    }

    /**
     * Get the OFFSET part
     * @return string
     */
    public function getOffset()
    {
        $offset = $this->get('sp:offset');
        if($offset) {
            return $offset->getValue();
        }
        return null;
    }

    /**
     * Get the SPARQL representation of the query
     * @return string
     */
    public function getSparql()
    {
        $sparql = "";
        if($this->getComment()) {
            $sparql .= "# ".$this->getComment()."\n";
        }

        // query keyword
        if($this::SPARQL_QUERY_KEYWORD) {
            $sparql .= $this::SPARQL_QUERY_KEYWORD;
        }

        // pattern
        $pattern = $this->getPattern();
        if($pattern) {
            $sparql .= " $pattern";
        }

        // where
        $where = $this->getWhere();
        if($where) {
            $sparql .= " WHERE { $where }";
        }

        // order
        $orderBy = $this->getOrderBy();
        if($orderBy) {
            $sparql .= " ORDER BY $orderBy";
        }

        // limit
        $limit = $this->getLimit();
        if($limit) {
            $sparql .= " LIMIT $limit";
        }

        // offset
        $offset = $this->getOffset();
        if($offset) {
            $sparql .= " OFFSET $offset";
        }

        return $sparql;
    }

}
