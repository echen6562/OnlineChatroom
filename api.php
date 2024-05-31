<?php

// get a command from the user
$command = $_GET['command'];
date_default_timezone_set('America/New_York');


if ($command == 'add') {

    // add a message into the chatroom

    // get message
    $message = $_GET['message'];
    $username = $_GET['username'];
    $room = $_GET['room'];
    $ip = $_SERVER['REMOTE_ADDR'];
    $timestamp = $_SERVER['REQUEST_TIME'];
    $time = date('Y-m-d H:i:s', $timestamp);

    // store the message into our database
    $db = new SQLite3( getcwd() . '/databases/messages.db' );

    // construct a query
    $sql = "INSERT INTO messages (username, message, room, ip, time) VALUES (:username, :message, :room, :ip, :time)";
    $statement = $db->prepare($sql);
    $statement->bindValue(':username', $username);
    $statement->bindValue(':message', $message);
    $statement->bindValue(':room', $room);
    $statement->bindValue(':ip', $ip);
    $statement->bindValue(':time', $time);


    // run the query
    $result = $statement->execute();

    // see if it worked
    $rows_affected = $db->changes();

    $db->close();
    unset($db);

    if ($rows_affected > 0) {
        print "ok";
        print $username;
        print $message;
        print $room;
    }
    else {
        print "error";
    }


}


if ($command == 'get') {

    $room = $_GET['room'];

    // store the message into our database
    $db = new SQLite3( getcwd() . '/databases/messages.db' );

    $sql = "SELECT id, username, message, room FROM messages ORDER BY id";
    $statement = $db->prepare($sql);
    $result = $statement->execute();

    // set up a return array
    $return_array = [];
    while ($row = $result->fetchArray()) {

        $id = $row[0];
        $username = $row[1];
        $message = $row[2];
        $currentRoom = $row[3];
        if($currentRoom == $room){
            array_push( $return_array, "$username: $message" );
        }
    }

    $db->close();
    unset($db);
   
    print json_encode($return_array);
}

if ($command == 'delete') {

    $room = $_GET['room'];

    // store the message into our database
    $db = new SQLite3( getcwd() . '/databases/messages.db' );

    $sql = "DELETE FROM messages WHERE room = :room";
    $statement = $db->prepare($sql);
    $statement->bindValue(':room', $room);
    $result = $statement->execute();

    $db->close();
    unset($db);
}

if($command == 'logs'){
    // store the message into our database
    $db = new SQLite3( getcwd() . '/databases/messages.db' );

    $sql = "SELECT * FROM messages";;
    
    $statement = $db->prepare($sql);
    $result = $statement->execute();

    // set up a return array
    $return_array = [];
    while ($row = $result->fetchArray()) {

        $id = $row[0];
        $username = $row[1];
        $message = $row[2];
        $currentRoom = $row[3];
        $ip = $row[4];
        $time = $row[5];
        array_push( $return_array, "$id | $username | $message | $currentRoom | $ip | $time" );
    }

    $db->close();
    unset($db);
   
    print json_encode($return_array);
}

?>





