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

function mod($num, $mod) {
  return ($mod + ($num % $mod)) % $mod;
}

 Class Node {
    function __construct($ducks, $parent=null){
        $this->ducks = $ducks;
        $this->parent = $parent;
    }

    function getWinId(){
        $ducks = $this->ducks;
        sort($ducks);
        return json_encode($ducks);
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

    function getVariant($source_index, $direction){
        $target_index_raw = $direction == 'right' ?
                            $source_index + $this->ducks[$source_index] :
                            $source_index - $this->ducks[$source_index];
        $target_index = mod($target_index_raw, count($this->ducks));
        $new_ducks = $this->ducks;
        $new_ducks[$source_index] = $this->ducks[$target_index];
        $new_ducks[$target_index] = $this->ducks[$source_index];
        return new Node($new_ducks, $this);
    }

    function getVariants(){
        $variants = [];
        foreach($this->ducks as $i=>$duck){
            $variants[] = $this->getVariant($i, 'right');
            $variants[] = $this->getVariant($i, 'left');
        }
        return $variants;
    }

}

$best_win = null;
$nodes = [];

function solve($node){
    global $best_win, $nodes;

    // if we're already over the best_win level
    if(!is_null($best_win) && $node->getMoves() >= $best_win){
        return;
    }

    // if we've gotten here before AND in fewer or equal moves...
    if(isset($nodes[$node->getId()]) &&
       $nodes[$node->getId()]->getMoves() <= $node->getMoves()){
        // ... bail
        return;
    }

    // otherwise we're safe... so add it to this list
    $nodes[$node->getId()] = $node;

    // if this is a win
    if($node->isWin()){
        // you can record it as best since clause above would already
        // have kicked us out if we'd found our way here before in
        // fewer steps
        $best_win = $node->getMoves();
    }else{
        // and move on with every possible move
        foreach($node->getVariants() as $new_node){
            solve($new_node);
        }
    }
}

$node = new Node([7,6,5,4,3,2,1]);
solve($node);
if(isset($nodes[$node->getWinId()])){
    $final = $nodes[$node->getWinId()];
    print_r($final);
    echo "min moves: ".$final->getMoves()."\n";
}
