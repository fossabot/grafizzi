<?php

/**
 * @file
 * Grafizzi\Graph\Tests\IG20bTest: a component of the Grafizzi library.
 *
 * (c) 2012 Frédéric G. MARAND <fgm@osinet.fr>
 *
 * Grafizzi is free software: you can redistribute it and/or modify it under the
 * terms of the GNU Lesser General Public License as published by the Free
 * Software Foundation, either version 3 of the License, or (at your option) any
 * later version.
 *
 * Grafizzi is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE.  See the GNU Lesser General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with Grafizzi, in the COPYING.LESSER.txt file.  If not, see
 * <http://www.gnu.org/licenses/>
 */

namespace Grafizzi\Graph\Tests;

use Grafizzi\Graph\Attribute;
use Grafizzi\Graph\Cluster;
use Grafizzi\Graph\Edge;
use Grafizzi\Graph\Graph;
use Grafizzi\Graph\Node;

require 'vendor/autoload.php';

/**
 * A recreation of Image_GraphViz test20b.phpt
 *
 * Image_GraphViz version author: Philippe Jausions <jausions@php.net>
 *
 * Test 20b: "Graph with edges on clusters not 'cluster'-named IDs"
 *
 * "Graph definition taken from GraphViz documentation"
 */
class IG20bTest extends BaseGraphTest {

  public function setUp($name = 'G', $attributes = array()) {
    // not strict by default.
    parent::setUp();
    $graph = $this->Graph;
    $dic = $this->dic;
    $graph->setDirected(true);
    $graph->setAttributes(array(
      new Attribute($dic, 'compound', true),
    ));


    $nullTitle = array(new Attribute($dic, 'title', NULL));

    $graph->addChild($cluster0 = new Cluster($dic, 0, $nullTitle));
    foreach (array('a', 'b', 'c', 'd') as $name) {
      $cluster0->addChild($$name = new Node($dic, $name));
    }

    $graph->addChild($cluster1 = new Cluster($dic, 1, $nullTitle));
    foreach (array('e', 'f', 'g') as $name) {
      $cluster1->addChild($$name = new Node($dic, $name));
    }

    $graph->addChild(new Edge($dic, $a, $b));
    $graph->addChild(new Edge($dic, $a, $c));
    $graph->addChild(new Edge($dic, $b, $d));

    // Note how we use getBuildName() instead of getName() for lhead/ltail,
    // because this are the strings GraphViz expects.
    $graph->addChild(new Edge($dic, $b, $f, array(
      new Attribute($dic, 'lhead', $cluster1->getBuildName()),
    )));
    $graph->addChild(new Edge($dic, $c, $d));
    $graph->addChild(new Edge($dic, $c, $g, array(
      new Attribute($dic, 'ltail', $cluster0->getBuildName()),
      new Attribute($dic, 'lhead', $cluster1->getBuildName()),
    )));
    $graph->addChild(new Edge($dic, $c,  $e, array(
      new Attribute($dic, 'ltail', $cluster0->getBuildName()),
    )));
    $graph->addChild(new Edge($dic, $e, $g));
    $graph->addChild(new Edge($dic, $e, $f));
    $graph->addChild(new Edge($dic, $d, $e));
    $graph->addChild(new Edge($dic, $d, $h = new Node($dic, 'h', array('implicit' => true))));
  }

  /**
   * Tests g->build()
   */
  public function testBuild() {
    $expected = <<<'EOT'
digraph G {
  compound=true;

  subgraph cluster_0 {
    a;
    b;
    c;
    d;
  } /* /subgraph cluster_0 */
  subgraph cluster_1 {
    e;
    f;
    g;
  } /* /subgraph cluster_1 */
  a -> b;
  a -> c;
  b -> d;
  b -> f [ lhead=cluster_1 ];
  c -> d;
  c -> g [ ltail=cluster_0, lhead=cluster_1 ];
  c -> e [ ltail=cluster_0 ];
  e -> g;
  e -> f;
  d -> e;
  d -> h;
} /* /digraph G */

EOT;
    $this->check($expected, "Image_graphViz test 20b passed.");
  }
}