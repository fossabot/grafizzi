<?php
namespace Grafizzi\Graph\GraphViz;

class GraphAttribute extends AbstractAttribute {
  public static $fDefaults = array(
    'bgcolor' => null, // color|colorlist
    'fontcolor' => 'black', // color
    'fontsize' => 14.0, // double, >= 1.0
    'label' => '', // string ("\n" on nodes)
    'rankdir' => 'TB', // rankDir: "TB", "LR", "BT", "RL"; dot only
  );
}
