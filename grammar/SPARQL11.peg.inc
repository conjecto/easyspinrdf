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

    QueryUnit: Query

   # _: WS*

    Query: Prologue ( SelectQuery | ConstructQuery | DescribeQuery | AskQuery ) ValuesClause

    UpdateUnit: Update

    Prologue: ( BaseDecl | PrefixDecl )*

    BaseDecl: 'BASE' IRIREF

    PrefixDecl: 'PREFIX' PNAME_NS IRIREF

    SelectQuery: s:SelectClause > d:DatasetClause* > w:WhereClause > sm:SolutionModifier

    SubSelect: s:SelectClause > w:WhereClause > sm:SolutionModifier > vc:ValuesClause

    SelectClause: 'SELECT'] (modifier:( 'DISTINCT' | 'REDUCED' ))? > ( vars:( <Var | expressions:( <'(' Expression ['AS'] Var ')' ) )+ | '*' )

    ConstructQuery: 'CONSTRUCT' ( ConstructTemplate DatasetClause* WhereClause SolutionModifier | DatasetClause* 'WHERE' '{' TriplesTemplate? '}' SolutionModifier )

    DescribeQuery: 'DESCRIBE' ( VarOrIri+ | '*' ) DatasetClause* WhereClause? SolutionModifier

    AskQuery: 'ASK' DatasetClause* WhereClause SolutionModifier

    DatasetClause: 'FROM' ( DefaultGraphClause | NamedGraphClause )

    DefaultGraphClause: SourceSelector

    NamedGraphClause:    'NAMED' SourceSelector

    SourceSelector:    iri

    */

    /*!* SPARQL11

    WhereClause:    'WHERE']? GroupGraphPattern

    SolutionModifier:    g:GroupClause? h:HavingClause? o:OrderClause? lo:LimitOffsetClauses?

    GroupClause:    'GROUP'] 'BY'] GroupCondition+

    GroupCondition:    BuiltInCall | FunctionCall | '(' Expression ( 'AS' Var )? ')' | Var

    HavingClause:    'HAVING'] HavingCondition+

    HavingCondition:    Constraint

    OrderClause:    'ORDER'] 'BY' ocs:( [ OrderCondition)+

    OrderCondition:     oc1:( ocAD:( 'ASC' | 'DESC' ) > ocBE:BrackettedExpression ) | ( Constraint | Var )

    LimitOffsetClauses:    l:LimitClause (< o:OffsetClause)? | o:OffsetClause (< l:LimitClause)?

    LimitClause:    'LIMIT'] limit:INTEGER

    OffsetClause:    'OFFSET'] offset:INTEGER

    ValuesClause:    ( 'VALUES' DataBlock )?

    Update:    Prologue ( Update1 ( ';' Update )? )?

    Update1:    Load | Clear | Drop | Add | Move | Copy | Create | InsertData | DeleteData | DeleteWhere | Modify

    Load:    'LOAD' 'SILENT'? iri ( 'INTO' GraphRef )?

    Clear:    'CLEAR' 'SILENT'? GraphRefAll

    Drop:    'DROP' 'SILENT'? GraphRefAll

    Create:    'CREATE' 'SILENT'? GraphRef

    */

    /*!* SPARQL11

    Add:    'ADD' 'SILENT'? GraphOrDefault 'TO' GraphOrDefault

    Move:    'MOVE' 'SILENT'? GraphOrDefault 'TO' GraphOrDefault

    Copy:    'COPY' 'SILENT'? GraphOrDefault 'TO' GraphOrDefault

    InsertData:    'INSERT DATA' QuadData

    DeleteData:    'DELETE DATA' QuadData

    DeleteWhere:    'DELETE WHERE' QuadPattern

    Modify:    ( 'WITH' iri )? ( DeleteClause InsertClause? | InsertClause ) UsingClause* 'WHERE' GroupGraphPattern

    DeleteClause:    'DELETE' QuadPattern

    InsertClause:    'INSERT' QuadPattern

    UsingClause:    'USING' ( iri | 'NAMED' iri )

    GraphOrDefault:    'DEFAULT' | 'GRAPH'? iri

    GraphRef:    'GRAPH' iri

    GraphRefAll:    GraphRef | 'DEFAULT' | 'NAMED' | 'ALL'

    QuadPattern:    '{' Quads '}'

    QuadData:    '{' Quads '}'

    Quads:    TriplesTemplate? ( QuadsNotTriples '.'? TriplesTemplate? )*

    QuadsNotTriples:    'GRAPH' VarOrIri '{' TriplesTemplate? '}'

    TriplesTemplate:    TriplesSameSubject ( '.' TriplesTemplate? )?

    GroupGraphPattern:    '{'> p:( SubSelect | GroupGraphPatternSub ) <'}'

    GroupGraphPatternSub:    t1:TriplesBlock? t2:( GraphPatternNotTriples > '.'? > TriplesBlock? )*

    TriplesBlock:    a:TriplesSameSubjectPath > (n:( '.'> TriplesBlock? ))?

    GraphPatternNotTriples:    GroupOrUnionGraphPattern | OptionalGraphPattern | MinusGraphPattern | GraphGraphPattern | ServiceGraphPattern | Filter | Bind | InlineData

    OptionalGraphPattern:    'OPTIONAL' GroupGraphPattern

    GraphGraphPattern:    'GRAPH' VarOrIri GroupGraphPattern

    ServiceGraphPattern:    'SERVICE' 'SILENT'? VarOrIri GroupGraphPattern

    Bind:    'BIND' '(' Expression 'AS' Var ')'

    InlineData:    'VALUES' DataBlock

    */

    /*!* SPARQL11

    DataBlock:    InlineDataOneVar | InlineDataFull

    InlineDataOneVar:    Var '{' DataBlockValue* '}'

    InlineDataFull:    ( NIL | '(' Var* ')' ) '{' ( '(' DataBlockValue* ')' | NIL )* '}'

    DataBlockValue:    iri |  RDFLiteral |  NumericLiteral |  BooleanLiteral |  'UNDEF'

    MinusGraphPattern:    'MINUS' GroupGraphPattern

    GroupOrUnionGraphPattern:    GroupGraphPattern ( 'UNION' GroupGraphPattern )*

    Filter:    'FILTER' Constraint

    Constraint:    BrackettedExpression | BuiltInCall | FunctionCall

    FunctionCall:    iri ArgList

    ArgList:    NIL | '(' 'DISTINCT'? Expression ( ',' Expression )* ')'

    ExpressionList:    NIL | '(' Expression ( ',' Expression )* ')'

    ConstructTemplate:    '{' ConstructTriples? '}'

    ConstructTriples:    TriplesSameSubject ( '.' ConstructTriples? )?

    TriplesSameSubject:    VarOrTerm PropertyListNotEmpty |  TriplesNode PropertyList

    PropertyList:    <PropertyListNotEmpty>?

    PropertyListNotEmpty:    Verb ObjectList ( ';' ( Verb ObjectList )? )*

    Verb:    VarOrIri | 'a'

    ObjectList:    Object ( ',' Object )*

    Object:    GraphNode

    */

    /*!* SPARQL11

    TriplesSameSubjectPath:    v:(VarOrTerm PropertyListPathNotEmpty) |  t:(TriplesNodePath PropertyListPath)

    PropertyListPath:    <PropertyListPathNotEmpty>?

    PropertyListPathNotEmpty:    p:<( VerbPath | VerbSimple )> o:ObjectListPath ( ';' ( <( VerbPath | VerbSimple )> ObjectList )? )*

    VerbPath:    Path

    VerbSimple:    v:Var

    ObjectListPath:    ObjectPath ( ',' ObjectPath )*

    ObjectPath:    GraphNodePath

    Path:    PathAlternative

    PathAlternative:    PathSequence ( '|' PathSequence )*

    PathSequence:    PathEltOrInverse ( '/' PathEltOrInverse )*

    PathElt:    PathPrimary PathMod?

    PathEltOrInverse:    PathElt | '^' PathElt

    PathMod:    '?' | '*' | '+'

    PathPrimary:    iri | 'a' | '!' PathNegatedPropertySet | '(' Path ')'

    PathNegatedPropertySet:    PathOneInPropertySet | '(' ( PathOneInPropertySet ( '|' PathOneInPropertySet )* )? ')'

    PathOneInPropertySet:    iri | 'a' | '^' ( iri | 'a' )

    Integer:    INTEGER_T

    TriplesNode:    Collection |  BlankNodePropertyList

    BlankNodePropertyList:    '[' PropertyListNotEmpty ']'

    TriplesNodePath:    CollectionPath |  BlankNodePropertyListPath

    BlankNodePropertyListPath:    '[' PropertyListPathNotEmpty ']'

    Collection:    '(' GraphNode+ ')'

    CollectionPath:    '(' GraphNodePath+ ')'

    GraphNode:    VarOrTerm |  TriplesNode

    GraphNodePath:    VarOrTerm |  TriplesNodePath

    VarOrTerm:    Var | GraphTerm

    VarOrIri:    Var | iri

    Var:  varname:(VAR1 | VAR2)

    GraphTerm:    iri |  RDFLiteral |  NumericLiteral |  BooleanLiteral |  BlankNode |  NIL

    Expression:    ConditionalOrExpression

    ConditionalOrExpression:    ConditionalAndExpression ( '||' ConditionalAndExpression )*

    ConditionalAndExpression:    ValueLogical ( '&&' ValueLogical )*

    ValueLogical: RelationalExpression

    RelationalExpression: NumericExpression  ( '='  NumericExpression | '!='  NumericExpression | '<'  NumericExpression | '>'  NumericExpression | '<='  NumericExpression | '>='  NumericExpression | 'IN'  ExpressionList | 'NOT IN'  ExpressionList )?

    NumericExpression:    AdditiveExpression

    AdditiveExpression:    MultiplicativeExpression ( '+' MultiplicativeExpression | '-' MultiplicativeExpression | ( NumericLiteralPositive | NumericLiteralNegative ) ( ( '*' UnaryExpression ) | ( '/' UnaryExpression ) )* )*

    MultiplicativeExpression:    UnaryExpression ( '*' UnaryExpression | '/' UnaryExpression )*

    UnaryExpression:      '!' PrimaryExpression |  '+' PrimaryExpression |  '-' PrimaryExpression |  PrimaryExpression

    PrimaryExpression:    BrackettedExpression | BuiltInCall | iriOrFunction | RDFLiteral | NumericLiteral | BooleanLiteral | Var

    BrackettedExpression:    '('> Expression <')'

    BuiltInCall:      Aggregate |  'STR' '(' Expression ')' |  'LANG' '(' Expression ')' |  'LANGMATCHES' '(' Expression ',' Expression ')' |  'DATATYPE' '(' Expression ')'|  'BOUND' '(' Var ')' |  'IRI' '(' Expression ')' |  'URI' '(' Expression ')' |  'BNODE' ( '(' Expression ')' | NIL ) |  'RAND' NIL |  'ABS' '(' Expression ')' |  'CEIL' '(' Expression ')' |  'FLOOR' '(' Expression ')' |  'ROUND' '(' Expression ')' |  'CONCAT' ExpressionList |  SubstringExpression |  'STRLEN' '(' Expression ')' |  StrReplaceExpression |  'UCASE' '(' Expression ')' |  'LCASE' '(' Expression ')' |  'ENCODE_FOR_URI' '(' Expression ')' |  'CONTAINS' '(' Expression ',' Expression ')' |  'STRSTARTS' '(' Expression ',' Expression ')' |  'STRENDS' '(' Expression ',' Expression ')' |  'STRBEFORE' '(' Expression ',' Expression ')' |  'STRAFTER' '(' Expression ',' Expression ')' |  'YEAR' '(' Expression ')' |  'MONTH' '(' Expression ')' |  'DAY' '(' Expression ')' |  'HOURS' '(' Expression ')' |  'MINUTES' '(' Expression ')' |  'SECONDS' '(' Expression ')' |  'TIMEZONE' '(' Expression ')' |  'TZ' '(' Expression ')' |  'NOW' NIL |  'UUID' NIL |  'STRUUID' NIL |  'MD5' '(' Expression ')' |  'SHA1' '(' Expression ')' |  'SHA256' '(' Expression ')' |  'SHA384' '(' Expression ')' |  'SHA512' '(' Expression ')' |  'COALESCE' ExpressionList |  'IF' '(' Expression ',' Expression ',' Expression ')' |  'STRLANG' '(' Expression ',' Expression ')' |  'STRDT' '(' Expression ',' Expression ')' |  'sameTerm' '(' Expression ',' Expression ')' |  'isIRI' '(' Expression ')' |  'isURI' '(' Expression ')' |  'isBLANK' '(' Expression ')' |  'isLITERAL' '(' Expression ')' |  'isNUMERIC' '(' Expression ')' |  RegexExpression |  ExistsFunc |  NotExistsFunc

    RegexExpression:    'REGEX' '(' Expression ',' Expression ( ',' Expression )? ')'

    SubstringExpression:    'SUBSTR' '(' Expression ',' Expression ( ',' Expression )? ')'

    StrReplaceExpression:    'REPLACE' '(' Expression ',' Expression ',' Expression ( ',' Expression )? ')'

    */

    /*!* SPARQL11

    ExistsFunc:    'EXISTS' GroupGraphPattern

    NotExistsFunc:    'NOT' 'EXISTS' GroupGraphPattern

    Aggregate:      'COUNT' '(' 'DISTINCT'? ( '*' | Expression ) ')' | 'SUM' '(' 'DISTINCT'? Expression ')' | 'MIN' '(' 'DISTINCT'? Expression ')' | 'MAX' '(' 'DISTINCT'? Expression ')' | 'AVG' '(' 'DISTINCT'? Expression ')' | 'SAMPLE' '(' 'DISTINCT'? Expression ')' | 'GROUP_CONCAT' '(' 'DISTINCT'? Expression ( ';' 'SEPARATOR' '=' String )? ')'

    iriOrFunction:    iri ArgList?

    RDFLiteral:    String ( LANGTAG | ( '^^' iri ) )?

    NumericLiteral:    NumericLiteralUnsigned | NumericLiteralPositive | NumericLiteralNegative

    NumericLiteralUnsigned:    INTEGER_T |  DECIMAL |  DOUBLE

    NumericLiteralPositive:    INTEGER_POSITIVE |  DECIMAL_POSITIVE |  DOUBLE_POSITIVE

    NumericLiteralNegative:    INTEGER_NEGATIVE |  DECIMAL_NEGATIVE |  DOUBLE_NEGATIVE

    BooleanLiteral:    'true' |  'false'

    String:    STRING_LITERAL1 | STRING_LITERAL2 | STRING_LITERAL_LONG1 | STRING_LITERAL_LONG2

    iri:    IRIREF |  PrefixedName

    PrefixedName:    PNAME_LN | PNAME_NS

    BlankNode:    BLANK_NODE_LABEL |  ANON

    */


    /*!* SPARQL11
    # Productions for terminals:

    IRIREF:    '<' (/[^<>"{}|^'\]-[\x{00}-\x{20}]/)* '>'

    PNAME_NS:    PN_PREFIX? ':'

    PNAME_LN:    PNAME_NS PN_LOCAL

    BLANK_NODE_LABEL:    '_:' ( PN_CHARS_U | /[0-9]/ ) (( PN_CHARS | '.' )* PN_CHARS )?

    VAR1:    '?' vn:VARNAME

    VAR2:    '$' vn:VARNAME

    LANGTAG:    '@' /[a-zA-Z]/+ ('-' /[a-zA-Z0-9]/+)*

    INTEGER_T:    /[0-9]/+

    DECIMAL:    /[0-9]/* '.' /[0-9]/+

    DOUBLE:    /[0-9]/+ '.' /[0-9]/* EXPONENT | '.' /([0-9])/+ EXPONENT | /([0-9])/+ EXPONENT

    INTEGER_POSITIVE:    '+' INTEGER_T

    DECIMAL_POSITIVE:    '+' DECIMAL

    DOUBLE_POSITIVE:    '+' DOUBLE

    INTEGER_NEGATIVE:    '-' INTEGER_T

    DECIMAL_NEGATIVE:    '-' DECIMAL

    DOUBLE_NEGATIVE:    '-' DOUBLE

    */

    /*!* SPARQL11

    EXPONENT:    /[eE]/ /[+-]/? /[0-9]/+

    STRING_LITERAL1:    "\'" ( (/[^\x{27}\x{5C}\x{0A}\x{0D}]/) | ECHAR )* "\'"

    STRING_LITERAL2:    '"' ( (/[^\x{22}\x{5C}\x{0A}\x{0D}]/) | ECHAR )* '"'

    STRING_LITERAL_LONG1:    "\'\'\'" ( ( "\'" | "\'\'" )? ( /[^'\]/ | ECHAR ) )* "\'\'\'"

    STRING_LITERAL_LONG2:    '"""' ( ( '"' | '""' )? ( /[^"\]/ | ECHAR ) )* '"""'

    ECHAR:    ''\' /[tbnrf\"']/

    NIL:    '(' WS* ')'

    WS:    '\x20' | '\x09' | '\x0D' | '\x0A'

    ANON:    '[' WS* ']'

    PN_CHARS_BASE:    /[A-Z]/ | /[a-z]/ | /[\x{00C0}-\x{00D6}]/u | /[\x{00D8}-\x{00F6}]/u | /[\x{00F8}-\x{02FF}]/u | /[\x{0370}-\x{037D}]/u | /[\x{037F}-\x{1FFF}]/u | /[\x{200C}-\x{200D}]/u | /[\x{2070}-\x{218F}]/u | /[\x{2C00}-\x{2FEF}]/u | /[\x{3001}-\x{D7FF}]/u | /[\x{F900}-\x{FDCF}]/u | /[\x{FDF0}-\x{FFFD}]/u | /[\x{10000}-\x{EFFFF}]/u

    PN_CHARS_U:    PN_CHARS_BASE | '_'

    VARNAME:    ( PN_CHARS_U | /[0-9]/ ) ( PN_CHARS_U | /[0-9]/ )* | '/\x{00B7}/u' | /[\x{0300}-\x{036F}]/u | /[\x{203F}-\x{2040}]/u )*

    PN_CHARS:    PN_CHARS_U | '-' | /[0-9]/ | '/\x{00B7}/u' | /[\x{0300}-\x{036F}]/u | /[\x{203F}-\x{2040}]/u

    PN_PREFIX:    PN_CHARS_BASE (( PN_CHARS | '.')* PN_CHARS )?

    PN_LOCAL:    ( PN_CHARS_U | ':' | /[0-9]/ | PLX ) (( PN_CHARS | '.' | ':' | PLX )* ( PN_CHARS | ':' | PLX ) )?

    PLX:    PERCENT | PN_LOCAL_ESC

    PERCENT:    '%' HEX HEX

    HEX:    /[0-9]/ | /[A-F]/ | /[a-f]/

    PN_LOCAL_ESC:    ''\' ( '_' | '~' | '.' | '-' | '!' | '$' | '&' | "'" | '(' | ')' | '*' | '+' | ',' | ';' | '=' | '/' | '?' | '#' | '@' | '%' )


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
     * {@inheritdoc}
     *
     * @param $result
     * @param $subres
     * @param null $storetag
     */
    function store ( &$result, $subres, $storetag = NULL ) {
        parent::store($result, $subres, $storetag);
        if(isset($subres['return'])){
            if(!isset($result['return'])) {
                $result['return'] = $subres['return'];
            }else{
                if(is_array($result['return'])){
                    $result['return'][] = $subres['return'];
                }else{
                    $result['return'] = array($result['return'],$subres['return']);
                }
            }
        }
    }

    /**
     * Return the parsed query
     * @return EasySpinRdf_Query
     */
    public function parse()
    {
        $node = $this->match_SelectQuery();
        return $node['return'];
    }

    /**
     * SelectQuery : finalise
     * @param $result
     */
    public function SelectQuery__finalise(&$result)
    {
        $result['return'] = $this->graph->newBNode(array('sp:Select'));
        if(isset($result['s']['modifier'])) {
            switch($result['s']['modifier']['text']) {
                case "DISTINCT":
                    $result['return']->setDistinct(true);
                    break;
                case "REDUCED":
                    $result['return']->setReduced(true);
                    break;
            }
        }
        if(isset($result['s']['vars']['return'])) {
            $result['return']->add('sp:resultVariables', $this->graph->newBNode(array('rdf:List')));
            if(is_array($result['s']['vars']['return'])){
                foreach($result['s']['vars']['return'] as $var ){
                    $result['return']->get('sp:resultVariables')->append($var);
                }
            }else{
                $result['return']->get('sp:resultVariables')->append($result['s']['vars']['return']);
            }
        }
        $whereRdfCol = $this->graph->newBNode(array('rdf:List'));
        if(is_array($result['w']['return'])){
            foreach($result['w']['return'] as $element){
                $whereRdfCol->append($element);
            }
        }else{
            $whereRdfCol->append($result['w']['return']);
        }
        $result['return']->add('sp:where', $whereRdfCol);
        if(isset($result['sm']['o'])){
            $orderRdfCol = $this->graph->newBNode(array('rdf:List'));
            if(is_array($result['sm']['o']['return'])){
                foreach($result['sm']['o']['return'] as $element){
                    $orderRdfCol->append($element);
                }
            }else{
                $orderRdfCol->append($result['sm']['o']['return']);
            }
            $result['return']->add('sp:orderBy', $orderRdfCol);
        }
        if(isset($result['sm']['lo'])){
            if(isset($result['sm']['lo']['l'])){
                $result['return']->add('sp:limit', $result['sm']['lo']['l']['return']);
            }
            if(isset($result['sm']['lo']['o'])){
                $result['return']->add('sp:offset', $result['sm']['lo']['o']['return']);
            }
        }

    }

    /**
     * SelectClause : finalise
     * @param $result
     */
    public function SelectClause__finalise(&$result)
    {

    }

    /**
     * WhereClause : finalise
     * @param $result
     */
    public function WhereClause_finalise(&$result)
    {

    }

    /**
     * Create a new SubSelect
     *
     * @param $result
     */
    public function SubSelect__construct(&$result)
    {

    }
    /**
     * SubSelect : finalise
     * @param $result
     */
    public function SubSelect__finalise(&$result)
    {
        $tmp = $this->graph->newBNode(array('sp:SubQuery'));
        $this->SelectQuery__finalise($result);
        $this->graph->add($tmp, 'sp:query', $result['return']);
        //@todo make vc: ValuesClause rule with $tmp
        $result['return'] = $tmp;
    }

    /**
     * GroupGraphPattern : finalise
     * @param $result
     */
    public function GroupGraphPattern__finalise(&$result)
    {
        $result['return'] = array();
        if(isset($result['p']) && isset($result['p']['return'])){
            if(is_array($result['p']['return'])){
                foreach($result['p']['return'] as $block ){
                    if(is_array($block)){
                        foreach($block as $subBlock){
                            $result['return'][] = $subBlock;
                        }
                    }else{
                        $result['return'][] = $block;
                    }
                }
            }else{
                $result['return'][] = $result['p']['return'];
            }
        }
    }

    /**
     * GroupGraphPatternSub : finalise
     * @param $result
     */
    public function GroupGraphPatternSub__finalise(&$result)
    {
       if(isset($result['t1']['return'])){
           $result['return'] = $result['t1']['return'];
       }else{
           $result['return'] = array();
       }
       if(isset($result['t2']) && isset($result['t2']['return']))
            $result['return'][] = $result['t2']['return'];
    }

    /**
     * TriplesBlock : finalise
     * @param $result
     */
    public function TriplesBlock__finalise(&$result)
    {
        if(isset($result['a']) && isset($result['a']['return'])){
            $result['return'] = array($result['a']['return']);
        }else{
            $result['return'] = array();
        }
        if(isset($result['n']) && isset($result['n']['return']))
            $result['return'] = array_merge($result['return'],$result['n']['return']);

    }

    /**
     * GroupGraphPatternSub : GraphPatternNotTriples
     * @param $result
     * @param $sub
     */
    public function GroupGraphPatternSub_GraphPatternNotTriples(&$result, $sub)
    {

    }

    /**
     * TriplesSameSubjectPath : finalise
     * @param $result
     */
    public function TriplesSameSubjectPath__finalise(&$result)
    {
        $newBnode = $this->graph->newBNode(array('sp:TriplePattern'));
        $this->graph->add($newBnode, 'sp:subject', $result['v']['return'][0]);
        $this->graph->add($newBnode, 'sp:predicate', $result['v']['return'][1]['predicate']['return']);
        $this->graph->add($newBnode, 'sp:object', $result['v']['return'][1]['object']['return']);
        $result['return'] = $this->graph->resource($newBnode);
    }

    /**
     * PropertyListPathNotEmpty : finalise
     * @param $result
     */
    public function PropertyListPathNotEmpty__finalise(&$result)
    {
        $result['return']['predicate'] = $result['p'];
        $result['return']['object'] = $result['o'];
    }

    /**
     * VAR1 : finalise
     * @param $result
     */
    public function VAR1__finalise(&$result)
    {
        $newBnode = $this->graph->resource("_:".$result['vn']['text'],array('sp:Variable'));
        $this->graph->add($newBnode, 'sp:varName', $result['vn']['text']);
        $result['return'] = $this->graph->resource($newBnode);
    }

    /**
     * VAR2 : finalise
     * @param $result
     */
    public function VAR2__finalise(&$result)
    {
        $this->VAR1__finalise($result);
    }

    /**
     * Var : finalise
     * @param $result
     */
    public function Var__finalise(&$result)
    {

    }

    /**
     * OrderClause : finalise
     * @param $result
     */
    public function OrderClause__finalise(&$result)
    {
        $result['return'] = array();
        if(is_array($result['ocs']['return'])){
            foreach($result['ocs']['return'] as $ocs ){
                $result['return'][] = $ocs;
            }
        }else{
            $result['return'][] = $result['ocs']['return'];
        }
    }

    /**
     * OrderCondition : finalise
     * @param $result
     */
    public function OrderCondition__finalise(&$result)
    {
        if(isset($result['oc1'])){
            switch($result['oc1']['ocAD']['text']) {
                case "ASC":
                    $orderAD = $this->graph->newBNode(array('sp:Asc'));
                    break;
                case "DESC":
                    $orderAD = $this->graph->newBNode(array('sp:Desc'));
                    break;
            }
            $this->graph->add($orderAD, 'sp:expression',$result['oc1']['ocBE']['return']);
            $result['return'] = $orderAD ;
        }
    }

    /**
     * LimitClause : finalise
     * @param $result
     */
    public function LimitClause__finalise(&$result)
    {
        $result['return'] = intval($result['limit']['text']);
    }

    /**
     * OffsetClause : finalise
     * @param $result
     */
    public function OffsetClause__finalise(&$result)
    {
        $result['return'] = intval($result['offset']['text']);
    }
}
