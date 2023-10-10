<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

require('inc/config.php');

// get project by id
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];

    $query = "SELECT * FROM projects WHERE id = ?";

    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    $project = $result->fetch_assoc();

    echo json_encode($project);

    exit;
}

// get all projects
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $query = "SELECT * FROM projects";

    $result = $mysqli->query($query);

    $projects = [];
    while ($row = $result->fetch_assoc()) {
        $projects[] = $row;
    }

    echo json_encode($projects);

    exit;
}

// create new project
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];

    $query = "INSERT INTO projects (name, description) VALUES (?, ?)";

    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('ss', $name, $description);
    $stmt->execute();

    echo json_encode([
        'status' => 'success',
        'message' => 'Project created'
    ]);

    exit;
}

// update project
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $id = $_GET['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];

    $query = "UPDATE projects SET name = ?, description = ? WHERE id = ?";

    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('ssi', $name, $description, $id);
    $stmt->execute();

    echo json_encode([
        'status' => 'success',
        'message' => 'Project updated'
    ]);

    exit;
}

// delete project
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_GET['id'];

    $query = "DELETE FROM projects WHERE id = ?";

    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();

    echo json_encode([
        'status' => 'success',
        'message' => 'Project deleted'
    ]);

    exit;
}

?>