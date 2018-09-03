<?php

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