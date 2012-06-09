<?php

/**
 * @file
 * Grafizzi\Graph\Tests\EdgeTest: a component of the Grafizzi library.
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
use Grafizzi\Graph\Edge;
use Grafizzi\Graph\Node;

/**
 * Edge test case.
 */
class EdgeTest extends BaseGraphTest {

  /**
   * @var Edge
   */
  private $Edge;

  /**
   * @var Attribute
   */
  private $Attribute;

  /**
   * Prepares the environment before running a test.
   */
  protected function setUp($name = 'G', $attributes = array()) {
    parent::setUp();
    $src = new Node($this->dic, 'source');
    $dst = new Node($this->dic, 'destination');
    $this->Attribute = new Attribute($this->dic, 'label', 'Source to Destination');
    $this->Edge = new Edge($this->dic, $src, $dst);
    $this->Edge->setAttribute($this->Attribute);
    foreach (array($src, $dst, $this->Edge) as $child) {
      $this->Graph->addChild($child);
    }
  }

  /**
   * Cleans up the environment after running a test.
   */
  protected function tearDown() {
    $this->Edge = null;
    parent::tearDown();
  }

  /**
   * Tests Edge->__construct()
   */
  public function test__construct() {
    $this->assertEquals($this->Attribute, $this->Edge->getAttributeByName('label'),
      'Edge is correctly labeled');
  }

  /**
   * Tests Edge->build()
   *
   * @TODO test graph build
   */
  public function testBuild() {
    $dot = $this->Edge->build($this->Graph->getDirected());
    $this->assertEquals(<<<EOT
  source -> destination [ label="Source to Destination" ];

EOT
      , $dot, "Edge builds correctly");
  }

  // Normal attributes list on edge bound to root graph.
  public function testBuildAttributesNormal() {
    $this->Edge->removeAttribute($this->Attribute);
    $this->Edge->setAttributes(array(
      new Attribute($this->dic, 'foo', 'bar'),
      new Attribute($this->dic, 'baz', 'quux'),
    ));
    $expected = <<<EOT
  source -> destination [ foo=bar, baz=quux ];

EOT;
    $actual = $this->Edge->build();
    $this->assertEquals($expected, $actual);
  }

  // Attribute list with empty title in middle on edge bound to root graph.
  public function testBuildAttributesEmptyMiddle() {
    $this->Edge->removeAttribute($this->Attribute);
    $this->Edge->setAttributes(array(
      new Attribute($this->dic, 'foo', 'bar'),
      new Attribute($this->dic, 'title', ''),
      new Attribute($this->dic, 'baz', 'quux'),
    ));
    $expected = <<<EOT
  source -> destination [ foo=bar, baz=quux ];

EOT;
    $actual = $this->Edge->build();
    $this->assertEquals($expected, $actual);
  }

  // Attribute list with empty title as single attribute on edge bound to root graph.
  public function testBuildAttributesOnlyEmpty() {
    $this->Edge->removeAttribute($this->Attribute);
    $this->Edge->setAttributes(array(
      new Attribute($this->dic, 'title', ''),
    ));
    $expected = <<<EOT
  source -> destination;

EOT;
    $actual = $this->Edge->build();
    $this->assertEquals($expected, $actual);
  }

  // Attribute list with empty title as last attribute on edge bound to root graph.
  public function testBuildAttributesEmptyLast() {
    $this->Edge->removeAttribute($this->Attribute);
    $this->Edge->setAttributes(array(
      new Attribute($this->dic, 'foo', 'bar'),
      new Attribute($this->dic, 'baz', 'quux'),
      new Attribute($this->dic, 'title', ''),
    ));
    $expected = <<<EOT
  source -> destination [ foo=bar, baz=quux ];

EOT;
    $actual = $this->Edge->build();
    $this->assertEquals($expected, $actual);
  }

  /**
   * Tests Edge::getAllowedChildTypes()
   */
  public function testGetAllowedChildTypes() {
    $this->assertEmpty(Edge::getAllowedChildTypes());
  }

  /**
   * Tests Edge::getType()
   */
  public function testGetType() {
    $this->assertEquals('edge', $this->Edge->getType());
  }
}

