<?php
// this is all about connect to database.
$username="root";
$password="";
$server="localhost";
$database="parking";
$connect=mysqli_connect($server,$username,$password,$database);
if(!$connect){
    echo "there is problem on database connection .";
    return;
}
$userTable="CREATE TABLE IF NOT EXISTS users(
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30) NOT NULL,
    email VARCHAR(50) NOT NULL,
    password VARCHAR(50) NOT NULL,
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if(mysqli_query($connect,$userTable)){
    echo "table created successfully";
}else{
    echo "error creating table".mysqli_error($connect);
}

$userInputs="INSERT INTO users (username,email,password) VALUES ('mekunint werkie','jFV4I@example.com','mekunint123')";

if(mysqli_query($connect,$userInputs)){
    echo "data inserted successfully";
}else{
    echo "error inserting data".mysqli_error($connect);
}
// the above table for user registration the attributes are id,username,email,password,reg_date.
?>