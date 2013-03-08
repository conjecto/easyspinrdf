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
//EasyRdf_TypeMapper::set('sp:Filter', 'EasySpinRdf_Expression_Filter');
//EasyRdf_TypeMapper::set('sp:SubQuery', 'EasySpinRdf_Element_SubQuery');

/**
 * Class that represents an SPIN RDF resource
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
     * @param $resource
     * @return string
     * @todo Change the EasyRdf type mapper to define a default resource class and use a default getSparql method
     */
    public function resourceToSparql(EasyRdf_Resource $resource)
    {
        if(method_exists($resource, 'getSparql')) {
            return $resource->getSparql();
        }

        if(is_a($resource, 'EasyRdf_Literal')) {
            return $resource->getValue();
        }

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
}
