<?php

// Enable error reporting - REMOVE ME
error_reporting(E_ALL);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/inc/config.php';

$app = AppFactory::create();

// Get all projects
$app->get('/v1/projects', function (Request $request, Response $response) use ($mysqli) {
    $sql = "SELECT * FROM projects";
    $result = $mysqli->query($sql);

    $projects = [];

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $projects[] = $row;
        }
    }

    $response->getBody()->write(json_encode($projects, JSON_PRETTY_PRINT));
    return $response->withHeader('Content-Type', 'application/json');
});

// Get a single project
$app->get('/v1/projects/{id}', function (Request $request, Response $response, $args) use ($mysqli) {
    $id = $args['id'];

    $stmt = $mysqli->prepare("SELECT * FROM projects WHERE id = ?");
    $stmt->bind_param("i", $id);

    $stmt->execute();

    $result = $stmt->get_result();
    $project = $result->fetch_assoc();

    if ($project) {
        $response->getBody()->write(json_encode($project, JSON_PRETTY_PRINT));
        return $response->withHeader('Content-Type', 'application/json');
    } else {
        return $response->withStatus(404)->getBody()->write(json_encode(['error' => 'Project not found']));
    }
});

// Adding a new project
$app->post('/v1/projects', function (Request $request, Response $response) use ($mysqli) {
    $data = $request->getParsedBody();

    $name = $data['name'];
    $code = $data['code'];
    $description = $data['description'];

    $stmt = $mysqli->prepare("INSERT INTO projects (name, code, description) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $code, $description);

    if ($stmt->execute()) {
        return $response->withStatus(201)->getBody()->write(json_encode(['message' => 'Project added successfully']));
    } else {
        return $response->withStatus(500)->getBody()->write(json_encode(['error' => 'Failed to add project']));
    }
});

// Updating a project
$app->put('/v1/projects/{id}', function (Request $request, Response $response, $args) use ($mysqli) {
    $id = $args['id'];
    $data = $request->getParsedBody();

    $name = $data['name'];
    $code = $data['code'];
    $description = $data['description'];

    $stmt = $mysqli->prepare("UPDATE projects SET name = ?, code = ?, description = ? WHERE id = ?");
    $stmt->bind_param("sssi", $name, $code, $description, $id);

    if ($stmt->execute()) {
        return $response->getBody()->write(json_encode(['message' => 'Project updated successfully']));
    } else {
        return $response->withStatus(500)->getBody()->write(json_encode(['error' => 'Failed to update project']));
    }
});

// Deleting a project
$app->delete('/v1/projects/{id}', function (Request $request, Response $response, $args) use ($mysqli) {
    $id = $args['id'];

    $stmt = $mysqli->prepare("DELETE FROM projects WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        return $response->getBody()->write(json_encode(['message' => 'Project deleted successfully']));
    } else {
        return $response->withStatus(500)->getBody()->write(json_encode(['error' => 'Failed to delete project']));
    }
});

// Fly, my pretties!
$app->run();

?>