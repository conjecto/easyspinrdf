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

class EasySpinRdf_Utils
{
    public static function setTypeMappers()
    {
        EasyRdf_Namespace::set('spl', 'http://spinrdf.org/spl#');
        EasyRdf_Namespace::set('spin', 'http://spinrdf.org/spin#');
        EasyRdf_Namespace::set('sp', 'http://spinrdf.org/sp#');

        EasyRdf_TypeMapper::set('sp:Ask', 'EasySpinRdf_Query_Ask');
        EasyRdf_TypeMapper::set('sp:Select', 'EasySpinRdf_Query_Select');
        EasyRdf_TypeMapper::set('sp:Describe', 'EasySpinRdf_Query_Describe');
        EasyRdf_TypeMapper::set('sp:Construct', 'EasySpinRdf_Query_Construct');

        // Elements
        EasyRdf_TypeMapper::set('sp:SubQuery', 'EasySpinRdf_Element_SubQuery');
        EasyRdf_TypeMapper::set('sp:NamedGraph', 'EasySpinRdf_Element_NamedGraph');
        EasyRdf_TypeMapper::set('sp:Optional', 'EasySpinRdf_Element_Optional');
        EasyRdf_TypeMapper::set('sp:Union', 'EasySpinRdf_Element_Union');
    }
}
