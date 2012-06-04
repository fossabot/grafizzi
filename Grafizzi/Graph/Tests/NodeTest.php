<?php

/**
 * @file
 * Grafizzi\Graph\Tests\NodeTest: a component of the Grafizzi library.
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

require 'vendor/autoload.php';

use Grafizzi\Graph\Attribute;
use Grafizzi\Graph\Node;

/**
 * Node test case.
 */
class NodeTest extends BaseGraphTest {

  /**
   *
   * @var Node
   */
  private $Node;

  /**
   * Prepares the environment before running a test.
   */
  protected function setUp() {
    parent::setUp();

    $this->Node = new Node($this->dic, 'n1');
  }

  /**
   * Cleans up the environment after running a test.
   */
  protected function tearDown() {
    // TODO Auto-generated NodeTest::tearDown()
    $this->Node = null;

    parent::tearDown();
  }

  /**
   * Tests Node->__construct()
   */
  public function test__construct() {
    $this->assertEquals('n1', $this->Node->getName());
  }

  /**
   * Tests Node->build()
   */
  public function testBuild() {
    // Test unbound node build.
    $expected = <<<EOT
n1;

EOT;
    $build = $this->Node->build();
    $this->assertEquals($expected, $build, 'Unbound node built correctly.');

    // Test build of node built at level 1.
    $this->Graph->addChild($this->Node);
    $expected = <<<EOT
  n1;

EOT;
    $build = $this->Node->build();
    $this->assertEquals($expected, $build, "Node bound at level 1 built correctly.");

    // Test build of node within a root graph.
    $expected = <<<EOT
digraph G {
  n1;
} /* /digraph G */

EOT;
    $build = $this->Graph->build();
    $this->assertEquals($expected, $build, "Graph with a single node built correctly.");
  }

  // Normal attributes list.
  public function testBuildAttributesNormal() {
    $this->Node->setAttributes(array(
      new Attribute($this->dic, 'foo', 'bar'),
      new Attribute($this->dic, 'baz', 'quux'),
    ));
    $expected = <<<EOT
n1 [ foo=bar, baz=quux ];

EOT;
    $actual = $this->Node->build();
    $this->assertEquals($expected, $actual);
  }

  // Attribute list with empty title in middle.
  public function testBuildAttributesEmptyMiddle() {
    $this->Node->setAttributes(array(
      new Attribute($this->dic, 'foo', 'bar'),
      new Attribute($this->dic, 'title', ''),
      new Attribute($this->dic, 'baz', 'quux'),
    ));
    $expected = <<<EOT
n1 [ foo=bar, baz=quux ];

EOT;
    $actual = $this->Node->build();
    $this->assertEquals($expected, $actual);
  }

  // Attribute list with empty title as single attribute.
  public function testBuildAttributesOnlyEmpty() {
    $this->Node->setAttributes(array(
      new Attribute($this->dic, 'title', ''),
    ));
    $expected = <<<EOT
n1;

EOT;
    $actual = $this->Node->build();
    $this->assertEquals($expected, $actual);
  }

  // Attribute list with empty title as last attribute.
  public function testBuildAttributesEmptyLast() {
    $this->Node->setAttributes(array(
      new Attribute($this->dic, 'foo', 'bar'),
      new Attribute($this->dic, 'baz', 'quux'),
      new Attribute($this->dic, 'title', ''),
    ));
    $expected = <<<EOT
n1 [ foo=bar, baz=quux ];

EOT;
    $actual = $this->Node->build();
    $this->assertEquals($expected, $actual);
  }

  /**
   * Tests Node::getAllowedChildTypes()
   */
  public function testGetAllowedChildTypes() {
    $this->assertEmpty(Node::getAllowedChildTypes());
  }

  /**
   * Tests Node::getType()
   */
  public function testGetType() {
    $this->assertEquals('node', Node::getType());
  }
}
