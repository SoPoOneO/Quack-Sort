<?php

require './vendor/autoload.php';
require './Capsule.php';

use Illuminate\Database\Capsule\Manager as Capsule;

$users = Capsule::table('boards')->get();

print_r($users->toArray());

// TABLE: bards
//  sequence,
//  min_moves,
//  best_win,
//  origin_id,
//  parent_id,
//  termination_reason: worked, win, repeat, worse-than-win

// while nodes found with null termination_reason
//      foreach node
//          if you can find a termination reason
//              mark it
//          else
//              create descendants

// search for winning node
// if found
//      present logically with paths seperated by moves and move count
// else
//      present "no wins possible"