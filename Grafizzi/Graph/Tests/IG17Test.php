<?php

/**
 * @file
 * Grafizzi\Graph\Tests\IG17Test: a component of the Grafizzi library.
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
 * A recreation of Image_GraphViz test17.phpt
 *
 * Image_GraphViz version author: Philippe Jausions <jausions@php.net>
 *
 * Test 17: "Process diagram with clusters"
 *
 * "Graph definition taken from GraphViz documentation"
 *
 * Note: ordering of insertions differs from Image_GraphViz, since Grafizzi
 * orders output by insertion order to allow customizing output order.
 */
class IG17Test extends BaseGraphTest {

  public function setUp($name = 'G', $attributes = array()) {
    // not strict by default.
    parent::setUp();
    $g = $this->Graph;
    $dic = $this->dic;
    $g->setDirected(true);

    $nullTitle = array(new Attribute($dic, 'title', NULL));

    // Global
    $g->addChild($start = new Node($dic, 'start', array(
      new Attribute($dic, 'shape', 'Mdiamond'),
    )));
    $g->addChild($end = new Node($dic, 'end', array(
      new Attribute($dic, 'shape', 'Msquare'),
    )));

    // cluster0
    $g->addChild($cluster0 = new Cluster($dic, 0, array(
      new Attribute($dic, 'style', 'filled'),
      new Attribute($dic, 'color', 'lightgrey'),
      new Attribute($dic, 'label', 'process #1'),
    )));
    for ($i = 0 ; $i < 4 ; $i++) {
      $nodeName = "a$i";
      $cluster0->addChild($$nodeName = new Node($dic, $nodeName, $nullTitle));
    }

    // cluster1
    $g->addChild($cluster1 = new Cluster($dic, 1, array(
      new Attribute($dic, 'color', 'blue'),
      new Attribute($dic, 'label', 'process #2'),
    )));
    for ($i = 0 ; $i < 4 ; $i++) {
      $nodeName = "b$i";
      $cluster1->addChild($$nodeName = new Node($dic, $nodeName, $nullTitle));
    }

    $g->addChild(new Edge($dic, $a0, $a1));
    $g->addChild(new Edge($dic, $a1, $a2));
    $g->addChild(new Edge($dic, $a1, $b3));
    $g->addChild(new Edge($dic, $a2, $a3));
    $g->addChild(new Edge($dic, $b0, $b1));
    $g->addChild(new Edge($dic, $b1, $b2));
    $g->addChild(new Edge($dic, $b2, $b3));
    $g->addChild(new Edge($dic, $b2, $a3));

    $g->addChild(new Edge($dic, $start, $a0));
    $g->addChild(new Edge($dic, $start, $b0));

    $g->addChild(new Edge($dic, $a3, $a0));
    $g->addChild(new Edge($dic, $a3, $end));
    $g->addChild(new Edge($dic, $b3, $end));
  }

  /**
   * Tests g->build()
   */
  public function testBuild() {
    $expected = <<<'EOT'
digraph G {
  start [ shape=Mdiamond ];
  end [ shape=Msquare ];
  subgraph cluster_0 {
    style=filled;
    color=lightgrey;
    label="process #1";

    a0;
    a1;
    a2;
    a3;
  } /* /subgraph cluster_0 */
  subgraph cluster_1 {
    color=blue;
    label="process #2";

    b0;
    b1;
    b2;
    b3;
  } /* /subgraph cluster_1 */
  a0 -> a1;
  a1 -> a2;
  a1 -> b3;
  a2 -> a3;
  b0 -> b1;
  b1 -> b2;
  b2 -> b3;
  b2 -> a3;
  start -> a0;
  start -> b0;
  a3 -> a0;
  a3 -> end;
  b3 -> end;
} /* /digraph G */

EOT;
    $this->check($expected, "Image_graphViz test 17 passed.");
  }
}
