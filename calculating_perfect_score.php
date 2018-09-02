<?php

// If we get back to anywhere we've ever been.. in >= number moves it took to get there before... 
//     stop that branch
// If we hit a win state
//     stop that branch
//     if this is the best win state so far...
//         record as best scrore
// If all branches end
//     if best score exists
//         report taht
//     else
//         report board as unwinnable

// each node:
//     key = md5(json(board))
//     parent_node
//     move: starting_index, go left or right
//     ending position
//     total number of moves to get there

 Class Node {
    function __construct($ducks, $parent=null){
        $this->parent = $parent;
        $this->ducks = $ducks;
    }

    function getWinId(){
        $ducks = $this->ducks;
        return json_encode(sort($ducks));
    }

    function isWin(){
        return $this->getId() === $this->getWinId();
    }

    function getId(){
        return json_encode($this->ducks);
    }

    function getMoves(){
        $moves = 0;
        $current_node = $this;
        while($current_node->parent){
            $current_node = $current_node->parent;
            $moves++;
        }
        return $moves;
    }

    function getVariants(){
        $variants = [];
        $ducks = $this->ducks;
        $d0 = $ducks[0];
        $d1 = $ducks[1];
        $d2 = $ducks[2];
        $variants = [
            new Node([$d0, $d2, $d1], $this),
            new Node([$d1, $d2, $d0], $this),
            new Node([$d2, $d0, $d1], $this)
        ];
        return $variants;

    }

}

function solve($node, $nodes=[], $best_win=null){
    // if we've gotten here before AND in fewer or equal moves...
    if(isset($nodes[$node->getId()]) &&
       $nodes[$node->getId()]->getMoves() <= $node->getMoves()){
        // ... bail
        return [];
    }

    // otherwise we're safe... so add it to this list
    $nodes[$node->getId()] = $node;

    if(!$node->isWin()){

        // and move on with every possible move
        foreach($node->getVariants() as $new_node){
            $nodes = array_merge($nodes, solve($new_node, $nodes));
        }
    }

    return $nodes;
}
