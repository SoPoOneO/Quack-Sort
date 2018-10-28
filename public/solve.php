<?php

$conn = getDataBaseConnection();

while($board = getUnhandledBoard()){
    setBoardResolution($board, 'terminated');
    print_r($board);
}

function setBoardResolution($board, $resolution){
    global $conn;
    $id = $board['id'];
    $sql = "UPDATE boards set `resolution` = '{$resolution}' where id = {$id}";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
}


function getDataBaseConnection(){
    $servername = "127.0.0.1";
    $username = "root";
    $password = "root";
    $dbname = "quack";

    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $conn;
}

function getUnhandledBoard(){
    global $conn;

    $sql = 'SELECT * FROM boards where `resolution` is null order by moves asc limit 1';

    $stmt = $conn->prepare($sql); 
    $stmt->execute(); 
    return $stmt->fetch();
}

// if all boards terminated
//     return best win
// else
//     choose lowest moves, non-handled board
//     if moves greater than or equal to best win
//         mark as terminated
//     else if equal to already found AND moves greater than or equal to that board's
//         mark as terminated
//     else
//         if win
//             mark as win
//         else
//             create all derivative boards
//             mark as handled
//             mark all equivalent boards as terminated and delete their descendants


// OUTCOMES: handled
//           terminated
//           win