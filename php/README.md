### Task 2.2.
Perform the attack from Figure 3.2 again. This time, however, include the user's login, hash and salt in the message body.
Tip: You can only enter one string of characters in the content field. So combine the desired data into one string with the CONCAT statement.
```
test','public',(SELECT CONCAT(login, " ", hash, " ", salt) AS Address FROM `user` WHERE 1 LIMIT 1),0)#
```

### Task 2.3.
Perform the attack from Figure 3.2 again. This time, however, include another user's
details in the body of the message.
Tip: You can use the OFFSET statement in your SQL command.
```
test','public',(SELECT CONCAT(login, " ", hash, " ", salt) AS Address FROM `user` WHERE 1 LIMIT 1 OFFSET 2),0)#
```

### Task 2.4.
Add the ability to edit messages to the application. Verify what SQLI attacks are possible against the added module.
```
security vulnerabilit
```

### Task 2.5.
Add a new database user. Define the minimum required set of permissions for it (show
what set it is in the report). Use the newly created account to connect the application to the database. Verify what has changed in terms of application security.
Tip. Try the attack in Figure 3.10 again.
```
mysql> CREATE USER 'app_user'@'%' IDENTIFIED BY 'secure_password';
Query OK, 0 rows affected (0.04 sec)

mysql> GRANT SELECT, INSERT, UPDATE, DELETE ON lab1.messages TO 'app_user'@'%';
Query OK, 0 rows affected (0.02 sec)

mysql> FLUSH PRIVILEGES;
Query OK, 0 rows affected (0.01 sec)

mysql> GRANT SELECT, INSERT, UPDATE, DELETE ON lab1.message TO 'app_user'@'%';
Query OK, 0 rows affected (0.00 sec)

mysql> FLUSH PRIVILEGES;
Query OK, 0 rows affected (0.00 sec)

INSERT INTO message (`name`,`type`, `message`,`deleted`) VALUES ('test','public',(SELECT CONCAT(login, " ", hash, " ", salt) AS Address FROM `user` WHERE 1 LIMIT 1 OFFSET 1),0)#','public',' 123',0)
Fatal error: Uncaught mysqli_sql_exception: SELECT command denied to user 'app_user'@'172.19.0.3' for table 'user' in /var/www/html/classes/Db.php:43 Stack trace: #0 /var/www/html/classes/Db.php(43): mysqli->query('INSERT INTO mes...') #1 /var/www/html/messages.php(11): Db->addMessage('test','public',...', 'public', ' 123') #2 {main} thrown in /var/www/html/classes/Db.php on line 43


Since 'app_user' has limited privileges, attempting to execute this query should result in an access denied error. This demonstrates the importance of granting only the minimum necessary permissions to database users for security reasons.
```

### Task 2.6.
Modify the application. Use only PDO to connect to the database. Place the code for
handling the database in the Db class. In each case, the data should be retrieved by a dedicated
function that is called in the PHP page code. The function should return the page a set of data
to display.
```
done
```


### Task 2.7.
Include user input filtering in your application.
```
done
```

### Task 2.8.
Apply whitelist to filter message type.
Tip: Notice that the message type is selected by the user from a list. This does not mean,
however, that you cannot insert any other string of characters there. This is possible using
developer tools that modify the content of the page (in the Chrome browser by pressing the
F12 button). Therefore, you need to verify whether the value transferred from this field is
included in the set (public, private).
```
done
```


### Task 2.8.
Modify the application. Develop comprehensive application security against SQLI attacks.
Create an additional Filter class. Include all the functionalities necessary to filter data. Add
filtering functions for data downloaded from the form. In the Db class, modify the functions
for adding data to the database so that each database function filters the data before using it.
The purpose of these modifications is to protect against programmer mistakes. He will not
have to remember to filter data. Each time the database function is called, the filter function
will be automatically invoked.
Tip: The presented approach to filtering and inserting data into the database may require
rebuilding database functions. The name, email address and URL will be filtered differently.
This problem can be solved in two ways:
1 Develop independent functions for different types of data:
1.1 addName
1.2 addURL
1.3 addEmail
2 Develop one generic function for inserting data and pass the type of filter that should be
used to the function as a parameter.
```
done
```


### Task 2.10.
Verify the vulnerability of the secured application to SQLI attacks. Conduct several
selected attacks on the application and present their results..
```
INSERT INTO messages (name,type,message) VALUES (?,?,?) Adding new message failed.
```
