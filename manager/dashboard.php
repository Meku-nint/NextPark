<?php
session_start();

// Simple authentication check: redirect to login if not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Manager Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
  <style>
  /* ... all your CSS styles unchanged ... */
  body{
    padding: 0;
    margin: 0;
  }
  .manage-admin {
    padding: 20px;
    border: 1px solid #ddd;
    width: 300px;
    margin: 20px auto;
    background-color: #f9f9f9;
    border-radius: 8px;
  }

  .manage-admin button {
    margin: 10px;
    padding: 10px;
    border: none;
    background-color: #007BFF;
    color: white;
    font-size: 16px;
    cursor: pointer;
    border-radius: 4px;
  }

  .manage-admin button:hover {
    background-color: #0056b3;
  }

  .manage-admin .form-container {
    margin-top: 10px;
  }

  .manage-admin input, select {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border-radius: 4px;
    border: 1px solid #ddd;
  }

  .manage-admin input[type="text"], input[type="email"] {
    font-size: 16px;
  }

  .manage-admin select {
    font-size: 16px;
  }

  .manage-admin h3 {
    margin-bottom: 10px;
  }
  .container {
    width: 250px;
    background-color: #333;
    color: white;
    position: fixed;
    height: 100%;
    top: 0;
    left: 0;
    overflow: auto;
  }

  .logo img {
    width: 100%;
    height: auto;
  }

  .container ul {
    list-style-type: none;
    padding: 0;
  }

  .container ul li {
    padding: 15px 20px;
  }

  .container ul li a {
    color: white;
    text-decoration: none;
    display: flex;
    align-items: center;
  }

  .container ul li a i {
    color: rgb(14, 221, 114);
    margin-right: 10px;
  }

  .container ul li:hover {
    background-color: #575757;
  }

  .content {
    margin-left: 270px;
    padding: 30px;
  }

  .content h2 {
    color: #0edd72;
  }

  img[alt="the image is not displayed"] {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 20px 0;
  }

  .section {
    margin-top: 40px;
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  }

  .section textarea {
    width: 100%;
    padding: 10px;
    font-size: 1rem;
    border-radius: 5px;
    border: 1px solid #ccc;
    resize: vertical;
  }

  .btn {
    background-color: #0edd72;
    color: white;
    padding: 10px 20px;
    margin-top: 10px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background 3s;
  }

  .btn:hover {
    background-color: #0bb15c;
  }

  #post-message {
    font-size: 1.2rem;
    margin-top: 15px;
    color: #0edd72;
  }
  .footer {
    background-color: #333;
    color: white;
    text-align: center;
    padding: 10px 0;
    position: fixed;
    bottom: 0;
    width: 100%;
  }
  .footer p {
    font-size: 14px;
    margin: 0;
  }
 
  @media screen and (max-width: 768px) {
    .container {
      width: 100%;
      position: relative;
    }
    .image-text img{
      display: none;
    }
    .image-text p{
      word-break: break-all;
    }

    .container img {
      display: none;
    }
    .content {
      margin-left: 0;
      padding: 20px;
    }
  }
  .home{
      gap: 50px;
      font-family: 'Times New Roman', Times, serif;
      max-width: 90%;
      margin: auto;
      }
      .home .data p{
        text-align: center;
        padding: 30px;
        box-shadow: 2px 2px 2px 2px rgb(156, 149, 149);
        font-family: 'Times New Roman', Times, serif;
        font-size: 25px;
        font-weight: lighter;
        border-left: 3px solid #075e31;
        border-radius: 20px;
      }
      .image-text{
        box-sizing:content-box;
        padding: 10px;
        background-color: rgb(192, 192, 234);
        display: flex;
        justify-content: space-evenly;
        gap: 19%;
      }
      .image-text p{
        font-size: 20px;
        font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
        font-display:fallback;

      }
.home .image-text img {
  max-width: 250px;
}
.bar{
  padding: 20px;
  font-family: 'Times New Roman', Times, serif;
  font-weight: lighter;
 margin: auto;
 background-color: rgb(4, 11, 72);
 text-align: center;
 color: white;

}
.reports ul{
padding: 10px;
border-left:12px solid #075e31;
background-color: #f4f4f4;
}
.reports ul li{
  list-style-type: none;
  padding: 10px;
  background-color:white;
  margin: 3px;
  border: 1px solid rgb(201, 190, 190);
}
ul li button{
  float: right;
  background-color:black;
  color: white;
  border: none;
  padding: 5px 10px;
  border-radius: 5px;
  cursor: pointer;
}
 </style>
</head>
<body>
  <div class="container">
    <div class="logo">
      <img src="https://cdn.pixabay.com/photo/2018/08/04/11/30/draw-3583548_640.png" alt="Logo" />
    </div>
    <ul>
      <li onclick="controlHome()"><a href="#home"><i class="fas fa-home"></i>Home</a></li>
      <li onclick="controlReport()"><a href="#see-reports" id="see-reports-link"><i class="fas fa-chart-line"></i>See Reports</a></li>
      <li onclick="controlPost()"><a href="#post"><i class="fas fa-upload"></i>Upload Something</a></li>
      <li onclick="controlManageAdmin()"><a href="manage_customer.php"><i class="fas fa-users-cog"></i>Manage customers</a></li>
      <li><a href="add_customer.php"><i class="fas fa-users-cog"></i>Add customer</a></li>
      <li><a href="">see your customers</a></li>
      <li><a href="#logout"><i class="fas fa-sign-out-alt"></i>Logout</a></li>
    </ul>
  </div>
  <h2 class="bar">Welcome to the main parking manager page </h2>
  <div class="content"id="content">
    <div class="section" id="see-reports">
      <h3>Reports</h3>
      <p>Here you can see the reports of the parking system.</p>
      <div class="reports">
        <ul>
          <li>here is report one <button>delete</button></li>
          <li>here is report one <button>delete</button></li>
          <li>here is report one <button>delete</button></li>
        </ul>
      </div>
      <button class="btn">View more Report</button>
    </div>
    <div class="section" id="upload-something">
      <h3>Post Something for Admins</h3>
      <textarea id="post" rows="4" placeholder="Write something for admins..."></textarea>
      <br />
      <button class="btn" id="post-button">Post</button>
      <div id="post-message"></div>
    </div>


  </div>
  <div class="footer">
    <p>© 2024 Parking System</p>
  </div>
  <script>
    function controlHome(){
      document.getElementById("content").scrollIntoView({behavior: 'smooth'});
    }
    function controlReport(){
      document.getElementById("see-reports").scrollIntoView({behavior: 'smooth'});
    }
    function controlPost(){
      document.getElementById("upload-something").scrollIntoView({behavior: 'smooth'});
    }
    function controlManageAdmin(){
      const adminSection = document.getElementById("manage-admin");
      if(adminSection.style.display === "none"){
        adminSection.style.display = "block";
      }else{
        adminSection.style.display = "none";
      }
      adminSection.scrollIntoView({behavior: 'smooth'});
    }

    document.getElementById("post-button").addEventListener("click", () => {
      const postContent = document.getElementById("post").value.trim();
      if(postContent){
        document.getElementById("post-message").textContent = "Posted: " + postContent;
        document.getElementById("post").value = "";
      } else {
        document.getElementById("post-message").textContent = "Please write something before posting.";
      }
    });

    function showAddAdminForm(){
      document.getElementById("add-admin-form").style.display = "block";
      document.getElementById("remove-admin-form").style.display = "none";
    }
    function showRemoveAdminForm(){
      document.getElementById("add-admin-form").style.display = "none";
      document.getElementById("remove-admin-form").style.display = "block";
    }
    function addAdmin(){
      const name = document.getElementById("admin-name").value.trim();
      const email = document.getElementById("admin-email").value.trim();
      if(name && email){
        alert(`Admin ${name} with email ${email} added.`);
        document.getElementById("admin-name").value = "";
        document.getElementById("admin-email").value = "";
      } else {
        alert("Please fill in both name and email.");
      }
    }
    function removeAdmin(){
      const email = document.getElementById("remove-admin-email").value.trim();
      if(email){
        alert(`Admin with email ${email} removed.`);
        document.getElementById("remove-admin-email").value = "";
      } else {
        alert("Please enter an email.");
      }
    }
  </script>
</body>
</html>