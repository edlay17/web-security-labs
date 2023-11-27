<?php
//phpinfo();
include_once "classes/Page.php";
include_once "classes/Db.php";
Page::display_header("Messages");
$db = new Db("172.19.0.2", "app_user", "secure_password", "lab1");
// adding new message
if (isset($_REQUEST['add_message'])) {
    // Validate and sanitize user input
    $name = isset($_REQUEST['name']) ? filter_var($_REQUEST['name'], FILTER_SANITIZE_STRING) : '';
    
    // Validate and ensure message type is in the whitelist
    $typeWhitelist = ['public', 'private'];
    $type = isset($_REQUEST['type']) && in_array($_REQUEST['type'], $typeWhitelist) ? $_REQUEST['type'] : '';
    
    $content = isset($_REQUEST['content']) ? filter_var($_REQUEST['content'], FILTER_SANITIZE_STRING) : '';

    // Validate input further (e.g., check length, format, etc.)
    if (empty($name) || empty($type) || empty($content)) {
        echo "Invalid input. Please provide all required fields.";
    } else {
        // Perform the database operation
        $data = [
            'name' => $name,
            'type' => $type,
            'message' => $content,
        ];
        //var_dump($data);

        if (!$db->addData('messages', $data)) {
            echo "Adding new message failed. Error: " . $db->getErrorMessage();
        } else {
            echo "Message added successfully.";
        }
    }
}

if (isset($_REQUEST['edit_message'])) {
    // Validate and sanitize user input
    $id = isset($_REQUEST['id']) ? filter_var($_REQUEST['id'], FILTER_VALIDATE_INT) : false;
    $newmessage = isset($_REQUEST['newmessage']) ? filter_var($_REQUEST['newmessage'], FILTER_SANITIZE_STRING) : '';

    // Validate input further (e.g., check for specific conditions)
    if ($id === false || empty($newmessage)) {
        echo "Invalid input. Please provide valid message ID and new message content.";
    } else {
        // Perform the database operation
        if (!$db->editMessage($id, $newmessage)) {
            echo "Editing message failed. Error: " . $db->mysqli->error;
        } else {
            echo "Message edited successfully.";
        }
    }
}
?>
<hr>
<P> Messages</P>
<ol>
<?php

 $sql = "SELECT * from message";
 $messages = $db->select($sql);
 foreach ($messages as $msg)://returned as objects
 echo "<li>";
 echo $msg->message ;
 echo "</li>";
 endforeach;
/*
$messages = $db->fetchMessages();

// Process and display the messages as needed
if ($messages) {
    foreach ($messages as $message) {
        // Process each message
        echo $message['name'] . ': ' . $message['message'] . '<br>';
    }
} else {
    echo "Error fetching messages.";
}
*/
 ?>
</ol>

<form method="post" action="messages.php">
 <table>
 <tr>
 <td>message id</td>
 <td>
 <input required type="text" name="id" id="id" size="4"/>
 </td>
 </tr>
 <tr>
 <td>new message</td>
 <td>
 <label for="newmessage"></label>
 <textarea required type="text" name="newmessage" id="newmessage" rows="10" cols="40">
 </textarea>
 </td>
 </tr>
 </table>
 <input type="submit" id="submit" value="edit message" name="edit_message">
</form>

<hr>
<P>Navigation</P>
<?php
Page::display_navigation();
?>
 </body>
</html>
