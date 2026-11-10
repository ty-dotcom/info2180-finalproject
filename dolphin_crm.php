<?php
$host = 'localhost';
$username = 'project_admin';
$password = 'password123';
$dbname = 'dolphin_crm';

$conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
$context = isset($_GET['context']) ? trim($_GET['context']) : '';

if ($context == 'users'){
  $stmt = $conn->query("SELECT users.id, users.firstname, users.lastname, contacts.title, users.password, users.email, users.Role, users.created_at FROM `users` LEFT JOIN `contacts` ON users.id=contacts.id");
  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

  ?>
  <table class="users-table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Created</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($results as $row): ?>
        <tr>
        <td><?= !empty($row['title']) ? htmlspecialchars($row['title'] ?? '') . '. ' : ''?><?=htmlspecialchars($row['firstname'])?> <?= htmlspecialchars($row['lastname'])?></td>
        <td><?= htmlspecialchars($row['email'])?></td>
        <td><?= htmlspecialchars($row['Role'])?></td>
        <td><?= htmlspecialchars($row['created_at'])?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
  <?php
}elseif ($context == 'contacts'){
  $filter = isset($_GET['filter']) ? trim($_GET['filter']) : '';
  if ($filter !== 'All'){
    if ($filter === 'Sales Leads' || $filter === 'Support'){
        $stmt = $conn->query("SELECT * FROM `contacts` WHERE type LIKE '%$filter%'");
    }else{
        // Session stuff
        $stmt = $conn->query("SELECT * FROM `contacts` WHERE type LIKE '%$filter%'");
    }
  }else{
    $stmt = $conn->query("SELECT * FROM `contacts`");
  }
  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

  ?>
  <table class="dashboard-table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Company</th>
            <th>Type</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($results as $row): ?>
        <tr>
        <td><?= htmlspecialchars($row['title'])?>. <?=htmlspecialchars($row['firstname'])?> <?= htmlspecialchars($row['lastname'])?></td>
        <td><?= htmlspecialchars($row['email'])?></td>
        <td><?= htmlspecialchars($row['company'])?></td>
        <?php if (htmlspecialchars($row['type']) === 'Sales Leads'): ?>
            <td><span class='type sales'><?= htmlspecialchars($row['type'])?></span></td>
        <?php else: ?>
            <td><span class='type'><?= htmlspecialchars($row['type'])?></span></td>
        <?php endif; ?>
        <td><a href="view_contact.html?id=<?= urlencode($row['id']) ?>" class="view_contacts">View</a></td>
        </tr>
    <?php endforeach; ?>
  <?php
}elseif($context === 'add_user'){
    header("Content-Type: application/json");
    if($_SERVER['REQUEST_METHOD'] !== 'POST'){
        http_response_code(405);
        echo json_encode(["status" => "error", "message" => "Method not allowed"]);
        exit;
    }

    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if (!$data) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Invalid Data"]);
        exit;
    }

    $firstname = isset($data['firstname']) ? trim($data['firstname']) : '';
    $lastname = isset($data['lastname']) ? trim($data['lastname']) : '';
    $email = isset($data['email']) ? trim($data['email']) : '';
    $password = isset($data['password']) ? trim($data['password']) : '';
    $role = isset($data['Role']) ? trim($data['Role']) : '';

    if (empty($firstname) || empty($lastname) || empty($email) || empty($password) || empty($role)){
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "All fields must be completed"]);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Inavlid email formatting"]);
        exit;
    }

    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/', $password)){
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Inavlid password"]);
        exit;
    }

    if (!in_array($role, ['Member', 'Admin'])){
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Inavlid role selected"]);
        exit;
    }

    $hashed = password_hash($password, PASSWORD_BCRYPT);
    try{
        $stmt = $conn->prepare("INSERT INTO `users` (firstname, lastname, password, email, Role) VALUES (:firstname, :lastname, :password, :email, :Role)");
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':Role', $role);

        $stmt->execute();
        http_response_code(200);
        echo json_encode(["status" => "success", "message" => "User added successfully!"]);

    } catch (PDOException $e){
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        exit;
    }

    
}




