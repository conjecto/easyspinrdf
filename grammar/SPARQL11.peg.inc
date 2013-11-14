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

    Query: Prologue ( SelectQuery | ConstructQuery | DescribeQuery | AskQuery )
    #ValuesClause

    UpdateUnit: Update

    Prologue: ( BaseDecl | PrefixDecl )*

    BaseDecl: 'BASE' IRIREF

    PrefixDecl: 'PREFIX' PNAME_NS IRIREF

    SelectQuery: SelectClause DatasetClause* WhereClause
    #  SolutionModifier

    SubSelect: SelectClause WhereClause SolutionModifier ValuesClause

    SelectClause: 'SELECT' [modifier:( 'DISTINCT' | 'REDUCED' )]? ( vars:( <Var | ( <'(' Expression 'AS' Var ')' ) )+ | '*' )

    ConstructQuery: 'CONSTRUCT' ( ConstructTemplate DatasetClause* WhereClause SolutionModifier | DatasetClause* 'WHERE' '{' TriplesTemplate? '}' SolutionModifier )

    DescribeQuery: 'DESCRIBE' ( VarOrIri+ | '*' ) DatasetClause* WhereClause? SolutionModifier

    AskQuery: 'ASK' DatasetClause* WhereClause SolutionModifier

    DatasetClause: 'FROM' ( DefaultGraphClause | NamedGraphClause )

    DefaultGraphClause: SourceSelector

    NamedGraphClause:    'NAMED' SourceSelector

    SourceSelector:    iri

    WhereClause:    ['WHERE']? GroupGraphPattern

    SolutionModifier:    GroupClause? HavingClause? OrderClause? LimitOffsetClauses?

    GroupClause:    'GROUP' 'BY' GroupCondition+

    GroupCondition:    BuiltInCall | FunctionCall | '(' Expression ( 'AS' Var )? ')' | Var

    HavingClause:    'HAVING' HavingCondition+

    HavingCondition:    Constraint

    OrderClause:    'ORDER' 'BY' OrderCondition+

    OrderCondition:     ( ( 'ASC' | 'DESC' ) BrackettedExpression ) | ( Constraint | Var )

    LimitOffsetClauses:    LimitClause OffsetClause? | OffsetClause LimitClause?

    LimitClause:    'LIMIT' INTEGER

    OffsetClause:    'OFFSET' INTEGER

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

    GroupGraphPattern:    '{'< ( SubSelect | GroupGraphPatternSub ) >'}'

    GroupGraphPatternSub:    <TriplesBlock>? ( GraphPatternNotTriples '.'? <TriplesBlock>? )*

    TriplesBlock:    TriplesSameSubjectPath ( '.' <TriplesBlock>? )?

    GraphPatternNotTriples:    GroupOrUnionGraphPattern | OptionalGraphPattern | MinusGraphPattern | GraphGraphPattern | ServiceGraphPattern | Filter | Bind | InlineData

    OptionalGraphPattern:    'OPTIONAL' GroupGraphPattern

    GraphGraphPattern:    'GRAPH' VarOrIri GroupGraphPattern

    ServiceGraphPattern:    'SERVICE' 'SILENT'? VarOrIri GroupGraphPattern

    Bind:    'BIND' '(' Expression 'AS' Var ')'

    InlineData:    'VALUES' DataBlock

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

    TriplesSameSubjectPath:    VarOrTerm PropertyListPathNotEmpty |  TriplesNodePath PropertyListPath

    PropertyListPath:    <PropertyListPathNotEmpty>?

    PropertyListPathNotEmpty:    <( VerbPath | VerbSimple )> ObjectListPath ( ';' ( <( VerbPath | VerbSimple )> ObjectList )? )*

    VerbPath:    Path

    VerbSimple:    Var

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

    Var:  VAR1 | VAR2

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

    BrackettedExpression:    '(' Expression ')'

    BuiltInCall:      Aggregate |  'STR' '(' Expression ')' |  'LANG' '(' Expression ')' |  'LANGMATCHES' '(' Expression ',' Expression ')' |  'DATATYPE' '(' Expression ')'|  'BOUND' '(' Var ')' |  'IRI' '(' Expression ')' |  'URI' '(' Expression ')' |  'BNODE' ( '(' Expression ')' | NIL ) |  'RAND' NIL |  'ABS' '(' Expression ')' |  'CEIL' '(' Expression ')' |  'FLOOR' '(' Expression ')' |  'ROUND' '(' Expression ')' |  'CONCAT' ExpressionList |  SubstringExpression |  'STRLEN' '(' Expression ')' |  StrReplaceExpression |  'UCASE' '(' Expression ')' |  'LCASE' '(' Expression ')' |  'ENCODE_FOR_URI' '(' Expression ')' |  'CONTAINS' '(' Expression ',' Expression ')' |  'STRSTARTS' '(' Expression ',' Expression ')' |  'STRENDS' '(' Expression ',' Expression ')' |  'STRBEFORE' '(' Expression ',' Expression ')' |  'STRAFTER' '(' Expression ',' Expression ')' |  'YEAR' '(' Expression ')' |  'MONTH' '(' Expression ')' |  'DAY' '(' Expression ')' |  'HOURS' '(' Expression ')' |  'MINUTES' '(' Expression ')' |  'SECONDS' '(' Expression ')' |  'TIMEZONE' '(' Expression ')' |  'TZ' '(' Expression ')' |  'NOW' NIL |  'UUID' NIL |  'STRUUID' NIL |  'MD5' '(' Expression ')' |  'SHA1' '(' Expression ')' |  'SHA256' '(' Expression ')' |  'SHA384' '(' Expression ')' |  'SHA512' '(' Expression ')' |  'COALESCE' ExpressionList |  'IF' '(' Expression ',' Expression ',' Expression ')' |  'STRLANG' '(' Expression ',' Expression ')' |  'STRDT' '(' Expression ',' Expression ')' |  'sameTerm' '(' Expression ',' Expression ')' |  'isIRI' '(' Expression ')' |  'isURI' '(' Expression ')' |  'isBLANK' '(' Expression ')' |  'isLITERAL' '(' Expression ')' |  'isNUMERIC' '(' Expression ')' |  RegexExpression |  ExistsFunc |  NotExistsFunc

    RegexExpression:    'REGEX' '(' Expression ',' Expression ( ',' Expression )? ')'

    SubstringExpression:    'SUBSTR' '(' Expression ',' Expression ( ',' Expression )? ')'

    StrReplaceExpression:    'REPLACE' '(' Expression ',' Expression ',' Expression ( ',' Expression )? ')'

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

    VAR1:    '?' VARNAME

    VAR2:    '$' VARNAME

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

    EXPONENT:    /[eE]/ /[+-]/? /[0-9]/+

    STRING_LITERAL1:    "\'" ( (/[^\x{27}\x{5C}\x{0A}\x{0D}]/) | ECHAR )* "\'"

    STRING_LITERAL2:    '"' ( (/[^\x{22}\x{5C}\x{0A}\x{0D}]/) | ECHAR )* '"'

    STRING_LITERAL_LONG1:    "\'\'\'" ( ( "\'" | "\'\'" )? ( /[^'\]/ | ECHAR ) )* "\'\'\'"

    STRING_LITERAL_LONG2:    '"""' ( ( '"' | '""' )? ( /[^"\]/ | ECHAR ) )* '"""'

    ECHAR:    ''\' /[tbnrf\"']/

    NIL:    '(' WS* ')'

    WS:    '\x20' | '\x09' | '\x0D' | '\x0A'

    ANON:    '[' WS* ']'

    PN_CHARS_BASE:    /[A-Z]/ | /[a-z]/
    # | /[\x{00C0}-\x{00D6}]/ | /[\x{00D8}-\x{00F6}]/ | /[\x{00F8}-\x{02FF}]/ | /[\x{0370}-\x{037D}]/ | /[\x{037F}-\x{1FFF}]/ | /[\x{200C}-\x{200D}]/ | /[\x{2070}-\x{218F}]/ | /[\x{2C00}-\x{2FEF}]/ | /[\x{3001}-\x{D7FF}]/ | /[\x{F900}-\x{FDCF}]/ | /[\x{FDF0}-\x{FFFD}]/ | /[\x{10000}-\x{EFFFF}]/

    PN_CHARS_U:    PN_CHARS_BASE | '_'

    VARNAME:    ( PN_CHARS_U | /[0-9]/ ) ( PN_CHARS_U | /[0-9]/ )*
    # | '/\x{00B7}/' | /[\x{0300}-\x{036F}]/ | /[\x{203F}-\x{2040}]/ )*

    PN_CHARS:    PN_CHARS_U | '-' | /[0-9]/
    # | '\x{00B7}' | /[\x{0300}-\x{036F}]/ | /[\x{203F}-\x{2040}]/

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
        if(isset($sub['vars'])) {
            $varsTab = explode(" ",$sub['vars']['text']);
            $result['query']->add('sp:resultVariables', $this->graph->newBNode(array('rdf:List')));
            foreach($varsTab as $var){
                $newBnode = $this->graph->newBNodeId();
                $this->graph->add($newBnode, 'sp:varName', substr($var,1));
                $result['query']->get('sp:resultVariables')->append($this->graph->resource($newBnode));
            }
        }
    }
}
