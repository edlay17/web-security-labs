<?php
/*
class Db {
    private $pdo; // PDO variable
    private $fetch_result; // result

    public function __construct($host, $user, $pass, $database) {
        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$database", $user, $pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->exec("SET NAMES 'utf8'");
        } catch (PDOException $e) {
            echo "Connection to server failed: " . $e->getMessage();
            exit();
        }
    }

    function __destruct() {
        $this->pdo = null;
    }

    public function fetchMessages() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM message WHERE deleted = 0");
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->fetch_result = $result;
            return $result;
        } catch (PDOException $e) {
            echo "Error fetching messages: " . $e->getMessage();
            return false;
        }
    }

    public function addMessage($name, $type, $content) {
        $sql = "INSERT INTO message (`name`, `type`, `message`, `deleted`) VALUES (?, ?, ?, 0)";
        echo $sql;
        echo "<BR\>";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$name, $type, $content]);
            return true;
        } catch (PDOException $e) {
            echo "Error adding message: " . $e->getMessage();
            return false;
        }
    }

    public function editMessage($message_id, $new_content) {
        $sql = "UPDATE message SET `message` = ? WHERE `id` = ?";
        echo $sql;
        echo "<BR\>";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$new_content, $message_id]);
            return true;
        } catch (PDOException $e) {
            echo "Error editing message: " . $e->getMessage();
            return false;
        }
    }

    public function getMessage($message_id) {
        foreach ($this->fetch_result as $message) {
            if ($message['id'] == $message_id) {
                return $message['message'];
            }
        }
        return null;
    }
}
*/

class Filter {
    public static function sanitizeString($input) {
        return filter_var($input, FILTER_SANITIZE_STRING);
    }

    public static function sanitizeEmail($input) {
        return filter_var($input, FILTER_SANITIZE_EMAIL);
    }

    public static function sanitizeURL($input) {
        return filter_var($input, FILTER_SANITIZE_URL);
    }
}

class Db {
    private $mysqli;

    public function __construct($server, $user, $pass, $database) {
        $this->mysqli = new mysqli($server, $user, $pass, $database);
        if ($this->mysqli->connect_errno) {
            printf("Connection to server failed: %s \n", $this->mysqli->connect_error);
            exit();
        }

        if ($this->mysqli->set_charset("utf8")) {
        }
    }

    function __destruct() {
        $this->mysqli->close();
    }

    private function insertData($tableName, $data, $filters) {
        foreach ($filters as $key => $filterArray) {
            foreach ($filterArray as $filter) {
                if (isset($data[$key])) {
                    $data[$key] = call_user_func($filter, $data[$key]);
                }
            }
        }

        $columns = implode(',', array_keys($data));
        $placeholders = implode(',', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO $tableName ($columns) VALUES ($placeholders)";

        echo $sql; // For testing purposes
        echo "<BR\>";

        $stmt = $this->mysqli->prepare($sql);
        if ($stmt) {
            $types = '';
            $values = [];
            foreach ($data as $key => $value) {
                $types .= 's'; // assuming all values are strings, adjust as needed
                $values[] = $value;
            }
            $stmt->bind_param($types, ...$values);
            $stmt->execute();
            $stmt->close();
            return true;
        } else {
            echo "Error preparing statement: " . $this->mysqli->error;
            return false;
        }
    }

    public function addData($tableName, $data) {
        $filters = [
            'name' => ['Filter::sanitizeString'],
            'type' => ['Filter::sanitizeString'],
            'message' => ['Filter::sanitizeString'],
            'category' => ['Filter::sanitizeString'], // Add category filter
        ];
    
        if (!$this->insertData($tableName, $data, $filters)) {
            echo "Adding new data failed. Error: " . $this->mysqli->error;
        } else {
            echo "Data added successfully.";
        }
    }

    public function select($sql) {
        $results = array();

        if ($result = $this->mysqli->query($sql)) {
            while ($row = $result->fetch_object()) {
                $results[] = $row;
            }

            $result->close();
        }

        return $results;
    }

    public function getErrorMessage() {
        return $this->mysqli->error;
    }
}

/*
class Db {
    private $mysqli; //Database variable
    private $select_result; //result

    public function __construct($serwer, $user, $pass, $baza) {
        $this->mysqli = new mysqli($serwer, $user, $pass, $baza);
        if ($this->mysqli->connect_errno) {
            printf("Connection to server failed: %s \n", $this->mysqli->connect_error);
            exit();
        }

        if ($this->mysqli->set_charset("utf8")) {}
    }

    function __destruct() {
        $this->mysqli->close();
    }

    public function select($sql) {
        //parameter $sql – select string
        //variable $results – association table with querry results

        $results=array();

        if ($result = $this->mysqli->query($sql)) {
            while ($row = $result->fetch_object()) {
                $results[]=$row;
            }

            $result->close();
        }
        
        $this->select_result=$results;

        return $results;
    }

    public function addMessage($name,$type,$content){
        $sql = "INSERT INTO message (`name`,`type`, `message`,`deleted`) VALUES ('" . $name . "','" . $type . "','" . $content . "',0)";
        echo $sql;
        echo "<BR\>";
        return $this->mysqli->query($sql);
    }

    public function editMessage($message_id, $new_content){
        $sql = "UPDATE message SET `message` = '" . $new_content . "' WHERE `id` = " . $message_id;
        echo $sql;
        echo "<BR\>";
        return $this->mysqli->query($sql);
    }

    public function getMessage($message_id){
        foreach ($this->select_result as $message):
            if($message->id==$message_id) return $message->message;
        endforeach;
    } 
}
*/
?>
