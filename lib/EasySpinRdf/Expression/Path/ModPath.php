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
 * Class that represents an SPIN ModPath expression
 *
 * @package    EasySpinRdf
 * @copyright  Conjecto - Blaise de Carné
 * @license    http://www.opensource.org/licenses/bsd-license.php
 */
class EasySpinRdf_Expression_Path_ModPath extends EasySpinRdf_Expression_Path
{
    /**
     * Get the SPARQL representation of the path expression
     */
    function getSparql()
    {
        $subPath = $this->get('sp:subPath');
        $modMin = $this->getLiteral('sp:modMin');
        $modMax = $this->getLiteral('sp:modMax');
        if(!$subPath) {
            throw new EasyRdf_Exception('The SPIN ModPath is not complete');
        }

        $min = !empty($modMin) ? $modMin->getValue() : 0;
        $max = !empty($modMax) ? $modMax->getValue() : 0;

        if($min && $max) {
            if($min == $max) {
                // min x, max x
                $modifier = '{'.$min.'}';
            } else {
                // min x, max y
                $modifier = '{'.$min.','.$max.'}';
            }
        } elseif($min == 1) {
            // min 1, max 0
            $modifier = '+';
        } elseif($max == 1) {
            // min 0, max 1
            $modifier = '?';
        } elseif($min && !$max) {
            // min x
            $modifier = '{'.$min.',}';
        } elseif(!$min && $max) {
            // max x
            $modifier = '{,'.$max.'}';
        } else {
            // min 0, max 0
            $modifier = '*';
        }

        return $this->resourceToSparql($subPath).$modifier;
    }
}
