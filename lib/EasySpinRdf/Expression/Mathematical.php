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
 * Abstract class that represents an SPIN mathematical expression
 *
 * @package    EasySpinRdf
 * @copyright  Conjecto - Blaise de Carné
 * @license    http://www.opensource.org/licenses/bsd-license.php
 */
abstract class EasySpinRdf_Expression_Mathematical extends EasySpinRdf_Expression
{
    /** mathematical operator */
    const SPARQL_MATHEMATICAL_OPERATOR = null;

    /**
     * Get the SPARQL representation of the mathematical expression
     */
    function getSparql()
    {
        if(!$this::SPARQL_MATHEMATICAL_OPERATOR) {
            throw new EasyRdf_Exception('The SPIN mathematical operator is not defined');
        }

        $arg1 = $this->get('sp:arg1');
        $arg2 = $this->get('sp:arg2');
        if(!$arg1 || !$arg2) {
            throw new EasyRdf_Exception('The SPIN mathematical expression is not complete');
        }

        $part1 = $this->resourceToSparql($arg1);
        if(is_a($arg1, 'EasySpinRdf_Expression_Mathematical'))
            $part1 = "(".$part1.")";

        $part2 = $this->resourceToSparql($arg2);
        if(is_a($arg2, 'EasySpinRdf_Expression_Mathematical'))
            $part2 = "(".$part2.")";

        return $part1.$this::SPARQL_MATHEMATICAL_OPERATOR.$part2;
    }
}
