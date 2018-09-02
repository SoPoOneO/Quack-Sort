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

}

$root = new Node([3,2,1]);
$second = new Node([3,1,2], $root);
$third = new Node([1,3,2], $second);

function solve($node, $nodes=[]){
    // if we've never gotten to this node, add it on
    if(!is_set($nodes[$node->getId()])){
        $nodes.push($node);
    // if we HAVE gotten to it before
    }else{
        $old_version = $nodes[$node->getId()];
        // add it on only if we got here faster this time
        if($node->getMoves() < $old_version){
            $nodes[$node->getId()] = $node;
        }
    }

}

print_r($third);
print_r($third->getMoves());