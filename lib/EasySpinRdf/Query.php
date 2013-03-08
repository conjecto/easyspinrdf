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
 * Class that represents an SPIN Query
 *
 * @package    EasySpinRdf
 * @copyright  Conjecto - Blaise de Carné
 * @license    http://www.opensource.org/licenses/bsd-license.php
 */
abstract class EasySpinRdf_Query extends EasySpinRdf_Resource
{
    /** query keyword */
    const SPARQL_KEYWORD = null;

    /**
     * Get the query comment
     * @return string
     */
    public function getComment()
    {
        return $this->get('rdfs:comment');
    }


    /**
     * Get the statement list from a SPIN query property
     * @param $property
     * @return bool|string
     * @todo : find a better way to manage subquery exception
     */
    public function getStatements($property)
    {
        $statements = $this->get($property);
        if(!$statements) {
            return false;
        }
        $sparql = "";
        foreach($statements as $statement) {
            if($sparql) {
                // special case for subquery exception
                if($statement->type() == 'sp:SubQuery')
                    $sparql .= ". ";
                else
                    $sparql .= "; ";
            }
            $sparql .= $this->getStatement($statement);
        }
        return $sparql;
    }

    /**
     * Get the statement from a resource
     * @param $resource
     * @return string
     * @throws Exception
     */
    public function getStatement(EasyRdf_Resource $resource)
    {
        if(method_exists($resource, 'getSparql')) {
            return $resource->getSparql();
        }

        $subject = $resource->get('sp:subject');
        $object = $resource->get('sp:object');
        $predicate = $resource->get('sp:predicate');

        if(!$subject || !$predicate || !$object) {
            throw new EasyRdf_Exception('The SPIN statement is not supported');
        }

        return $this->resourceToSparql($subject)." ".$this->resourceToSparql($predicate)." ".$this->resourceToSparql($object);
    }

    /**
     * Get the variables list
     * @return bool|string
     */
    public function getVariables()
    {
        $variables = $this->get('sp:resultVariables');
        if(!$variables) {
            return false;
        }
        $parts = array();
        foreach($variables as $variable) {
            $parts[] = $this->resourceToSparql($variable);
        }
        return join(" ", $parts);
    }

    /**
     * Get the templates part
     * @return string
     */
    public function getTemplates()
    {
        return $this->getStatements('sp:templates');
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
     * Get the LIMIT part
     * @return string
     */
    public function getLimit()
    {
        return $this->get('sp:limit');
    }

    /**
     * Get the SPARQL representation of the query
     * @return string
     */
    public function getSparql()
    {
        $sparql = "";
        if($this->getComment()) {
            $sparql .= "#".$this->getComment()."\n";
        }

        // query keyword
        if($this::SPARQL_KEYWORD) {
            $sparql .= $this::SPARQL_KEYWORD;
        }

        // variables
        $variables = $this->getVariables();
        if($variables) {
            $sparql .= " $variables";
        }

        // templates
        $templates = $this->getTemplates();
        if($templates) {
            $sparql .= " { $templates }";
        }

        // where
        $where = $this->getWhere();
        if($where) {
            $sparql .= " WHERE { $where }";
        }

        // limit
        $limit = $this->getLimit();
        if($limit) {
            $sparql .= " LIMIT $limit";
        }

        return $sparql;
    }

}
