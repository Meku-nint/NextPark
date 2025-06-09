<?php
session_start();
$name = $latitude = $longitude = $price = $info = "";
$successMessage = "";
$errors = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"] ?? "");
    $latitude = trim($_POST["latitude"] ?? "");
    $longitude = trim($_POST["longitude"] ?? "");
    $price = trim($_POST["price"] ?? "");
    $info = trim($_POST["info"] ?? "");
    if (empty($name)) {
        $errors[] = "Parking Name is required.";
    }
    if (!is_numeric($latitude)) {
        $errors[] = "Latitude must be a valid number.";
    }
    if (!is_numeric($longitude)) {
        $errors[] = "Longitude must be a valid number.";
    }
    if (!is_numeric($price)) {
        $errors[] = "Price must be a valid number.";
    }
    if (empty($errors)) {
        // For now, just simulate success
        $successMessage = "Parking information for <strong>" . htmlspecialchars($name) . "</strong> submitted successfully.";
        // Reset values
        $name = $latitude = $longitude = $price = $info = "";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Parking Information Entry</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f6f8;
      margin: 0;
      padding: 0;
    }

    .container {
      max-width: 600px;
      margin: 50px auto;
      background-color: #ffffff;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    h2 {
      text-align: center;
      color: #333;
      margin-bottom: 1.5rem;
    }

    label {
      display: block;
      margin: 0.75rem 0 0.25rem;
      font-weight: 600;
    }

    input, textarea {
      width: 100%;
      padding: 0.5rem;
      border: 1px solid #ccc;
      border-radius: 4px;
      font-size: 1rem;
    }

    textarea {
      resize: vertical;
    }

    button {
      margin-top: 1.5rem;
      padding: 0.75rem 1.5rem;
      background-color: #007bff;
      border: none;
      color: #fff;
      border-radius: 4px;
      font-size: 1rem;
      cursor: pointer;
      width: 100%;
    }

    button:hover {
      background-color: #0056b3;
    }

    .success {
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
      padding: 15px;
      border-radius: 4px;
      margin-bottom: 1rem;
    }

    .errors {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
      padding: 15px;
      border-radius: 4px;
      margin-bottom: 1rem;
    }
  </style>
</head>
<body>

  <div class="container">
    <h2>Enter Parking Information</h2>

    <?php if ($successMessage): ?>
      <div class="success"><?php echo $successMessage; ?></div>
    <?php endif; ?>

    <?php if ($errors): ?>
      <div class="errors">
        <ul>
          <?php foreach($errors as $error): ?>
            <li><?php echo htmlspecialchars($error); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="post" action="customer.php">
      <label for="name">Parking Name</label>
      <input type="text" id="org_name" name="org_name" placeholder="e.g., Bole Airport Parking" required value="<?php echo htmlspecialchars($name); ?>" />

      <label for="latitude">Latitude</label>
      <input type="number" id="latitude" name="latitude" step="any" placeholder="e.g., 9.037" required value="<?php echo htmlspecialchars($latitude); ?>" />

      <label for="longitude">Longitude</label>
      <input type="number" id="longitude" name="longitude" step="any" placeholder="e.g., 38.762" required value="<?php echo htmlspecialchars($longitude); ?>" />

      <label for="price">Price per Hour (ETB)</label>
      <input type="number" id="price" name="price" step="0.01" placeholder="e.g., 20.00" required value="<?php echo htmlspecialchars($price); ?>" />

      <label for="info">Additional Information</label>
      <textarea id="info" name="info" rows="4" placeholder="e.g., Covered parking, 24/7 security..."><?php echo htmlspecialchars($info); ?></textarea>

      <button type="submit">Submit Parking Info</button>
    </form>
  </div>

</body>
</html>