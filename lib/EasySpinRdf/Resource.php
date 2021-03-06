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
 * SPIN namespaces registering
 */
EasyRdf_Namespace::set('spl', 'http://spinrdf.org/spl#');
EasyRdf_Namespace::set('spin', 'http://spinrdf.org/spin#');
EasyRdf_Namespace::set('sp', 'http://spinrdf.org/sp#');

/**
 * EasyRdf type mapping
 */

// Elements
EasyRdf_TypeMapper::set('sp:TriplePattern', 'EasySpinRdf_Element_TriplePattern');
EasyRdf_TypeMapper::set('sp:SubQuery', 'EasySpinRdf_Element_SubQuery');
EasyRdf_TypeMapper::set('sp:NamedGraph', 'EasySpinRdf_Element_NamedGraph');
EasyRdf_TypeMapper::set('sp:Optional', 'EasySpinRdf_Element_Optional');
EasyRdf_TypeMapper::set('sp:Union', 'EasySpinRdf_Element_Union');
EasyRdf_TypeMapper::set('sp:Bind', 'EasySpinRdf_Element_Bind');
EasyRdf_TypeMapper::set('sp:Filter', 'EasySpinRdf_Element_Filter');
EasyRdf_TypeMapper::set('sp:TriplePath', 'EasySpinRdf_Element_TriplePath');

// Aggregation
EasyRdf_TypeMapper::set('sp:Avg', 'EasySpinRdf_Expression_Aggregation_Avg');
EasyRdf_TypeMapper::set('sp:Count', 'EasySpinRdf_Expression_Aggregation_Count');
EasyRdf_TypeMapper::set('sp:Max', 'EasySpinRdf_Expression_Aggregation_Max');
EasyRdf_TypeMapper::set('sp:Min', 'EasySpinRdf_Expression_Aggregation_Min');
EasyRdf_TypeMapper::set('sp:Sum', 'EasySpinRdf_Expression_Aggregation_Sum');
EasyRdf_TypeMapper::set('sp:Asc', 'EasySpinRdf_Expression_Aggregation_Asc');
EasyRdf_TypeMapper::set('sp:Desc', 'EasySpinRdf_Expression_Aggregation_Desc');

// Mathematical
EasyRdf_TypeMapper::set('sp:mul', 'EasySpinRdf_Expression_Mathematical_Multiply');
EasyRdf_TypeMapper::set('sp:add', 'EasySpinRdf_Expression_Mathematical_Add');
EasyRdf_TypeMapper::set('sp:sub', 'EasySpinRdf_Expression_Mathematical_Substract');
EasyRdf_TypeMapper::set('sp:divide', 'EasySpinRdf_Expression_Mathematical_Divide');

// Path
EasyRdf_TypeMapper::set('sp:SeqPath', 'EasySpinRdf_Expression_Path_SeqPath');
EasyRdf_TypeMapper::set('sp:AltPath', 'EasySpinRdf_Expression_Path_AltPath');
EasyRdf_TypeMapper::set('sp:ModPath', 'EasySpinRdf_Expression_Path_ModPath');
EasyRdf_TypeMapper::set('sp:ReversePath', 'EasySpinRdf_Expression_Path_ReversePath');


/**
 * Abstract class that represents an SPIN resource
 *
 * @package    EasySpinRdf
 * @copyright  Conjecto - Blaise de Carné
 * @license    http://www.opensource.org/licenses/bsd-license.php
 */
abstract class EasySpinRdf_Resource extends EasyRdf_Resource
{
    /**
     * Get the SPARQL representation of the resource
     * @return string
     */
    abstract public function getSparql();

    /**
     * Return a SPARQL representation of another resource
     * We have to do this because other resource does not always extend the EasySpinRdf_Resource element
     * @param $resource
     * @param $concat
     * @return string
     * @todo Change the EasyRdf type mapper to define a default resource class and use a default getSparql method
     */
    public function resourceToSparql($resource, $concat = " ")
    {
        // if the resource is a collection, we concat sparql with the $concat param
        if(is_a($resource, 'EasyRdf_Collection')) {
            $resources = array();
            foreach($resource as $item) {
                $resources[] = $this->resourceToSparql($item);
            }
            return join($concat, $resources);
        }

        // if the resource implement getSparql method, just use it
        if(method_exists($resource, 'getSparql')) {
            return $resource->getSparql();
        }

        // if the resource is a literal return its value
        if(is_a($resource, 'EasyRdf_Literal')) {
            $value = $resource->getValue();
            return is_int($value) ? $value : '"'.$value.'"';
        }

        // if the resource has a sp:varName property, use it as a variable
        if($varName = $resource->get('sp:varName')) {
            return "?".$varName->getValue();
        }

        // if the resource is a bnode, lets adapt it to SPIN specifications
        if($resource->isBNode()) {
            return '?'.substr($resource->getUri(), 2);
        }

        list($prefix, $suffix) = \EasyRdf_Namespace::splitUri($resource);
        if(in_array($prefix, array('sp', 'spin')) && $suffix[0] == "_") {
            $resource = '?'.substr($suffix, 1);
        } elseif(\EasyRdf_Namespace::shorten($resource)) {
            $resource = \EasyRdf_Namespace::shorten($resource);
        } else {
            $resource = "<".$resource.">";
        }
        return $resource;
    }

    /**
     * Get the statement list from a SPIN property
     * @param $property
     * @param $separator
     * @return bool|string
     */
    public function getStatements($property, $separator = ".")
    {
        $statements = $this->get($property);
        if(!$statements) {
            return false;
        }
        $parts = array();
        foreach($statements as $statement) {
            $parts[] = $this->getStatement($statement);
        }
        return join($separator." ", $parts);
    }

    /**
     * Get the statement from a resource
     * @param $resource
     * @return string
     * @throws EasyRdf_Exception
     * @todo : use TriplePattern
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
}
