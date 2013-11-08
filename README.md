EasySpinRdf
===========

[SPIN SPARQL Syntax](http://spinrdf.org/sp.html) implementation using the awesome [EasyRdf](http://www.easyrdf.org).

SPIN SPARQL Syntax is a machine-readable notation of SPARQL in RDF format. This EasyRdf extension convert SPIN RDF data structures into valid SPARQL query strings.

[![Build Status](https://travis-ci.org/conjecto/easyspinrdf.png?branch=master)](https://travis-ci.org/conjecto/easyspinrdf)

### Example ###

Take this RDF graph in ttl :

    my:query
      a sp:Ask ;
      sp:where (
        [ sp:object sp:_age ;
          sp:predicate my:age ;
          sp:subject spin:_this
        ] [ a sp:Filter ;
          sp:expression
            [ sp:arg1 sp:_age ;
              sp:arg2 18 ;
              a sp:lt
            ]
        ] )
    ]

Get the corresponding SPARQL Query with EasyRdf and EasySpinRdf :

    EasySpinRdf_Utils::setTypeMappers();
    $graph = new EasyRdf_Graph("http://conjecto.com/queries.ttl");
    $graph->load();
    echo $graph->get("my:query")->getSparql();

Result :

    ASK WHERE {
    	?this my:age ?age .
    	FILTER (?age < 18) .
    }

Links
-----

* [EasyRdf Homepage](http://www.easyrdf.org/)
* Source Code: <http://github.com/njh/easyspinrdf>
* Issue Tracker: <http://github.com/njh/easyspinrdf/issues>
* [Conjecto Homepage](http://www.conjecto.com/)

Todo
--------

* Reverse engineering
* [SPIN Modeling Vocabulary](http://spinrdf.org/spin.html) support
