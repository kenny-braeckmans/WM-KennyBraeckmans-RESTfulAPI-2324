<?php

/**
 * API for the project management app
 */

/**
 * Initialization
 */

// Enable error reporting - REMOVE/DISABLE IN PROD
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load Composer's autoloader for required libraries
require __DIR__ . '/vendor/autoload.php';

// Load the database configuration and helper functions
require __DIR__ . '/inc/config.php';
require __DIR__ . '/inc/helpers.php';

// Import necessary classes using the Composer autoloader
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

// Create a new Slim app instance
$app = AppFactory::create();

/**
 * Middleware configuration
 */

// Middleware to handle JSON request body parsing.
// This ensures the request's JSON body is parsed and made available as request attributes.
$app->addBodyParsingMiddleware();

// Middleware for CORS support.
$app->add(new Tuupola\Middleware\CorsMiddleware([
    "origin" => ["https://www.bennykraeckmans.be"],
    "methods" => ["GET", "POST", "PUT", "PATCH", "DELETE"],
    "headers.allow" => [],
    "headers.expose" => [],
    "credentials" => false,
    "cache" => 0,
]));

// Middleware to determine which route handles the request.
$app->addRoutingMiddleware();

/**
 * Routes for API endpoints
 */

// Get all projects
$app->get('/v1/projects', function (Request $request, Response $response) use ($mysqli) {
    $query = "SELECT *
                FROM projects
               ORDER BY ID";
    $result = $mysqli->query($query);

    $projects = [];

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $projects[] = $row;
        }
    }

    return jsonResponse($response, $projects, 200);
});

// Get a single project
$app->get('/v1/projects/{id}', function (Request $request, Response $response, $args) use ($mysqli) {
    $id = $args['id'];

    $query = "SELECT *
                FROM projects
               WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $id);

    $stmt->execute();

    $result = $stmt->get_result();
    $project = $result->fetch_assoc();

    if ($project) {
        return jsonResponse($response, $project, 200);
    } else {
        return jsonResponse($response, ['error' => 'Project not found'], 404);
    }
});

// Adding a new project (1)
$app->post('/v1/projects', function (Request $request, Response $response) use ($mysqli) {
    $data = $request->getParsedBody();

    $name = $data['name'];
    $code = $data['code'];
    $description = $data['description'];

    $query = "INSERT INTO projects (name,
                                    code,
                                    description)
              VALUES (?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("sss", $name, $code, $description);

    if ($stmt->execute()) {
        return jsonResponse($response, ['message' => 'Project added successfully'], 200);
    } else {
        return jsonResponse($response, ['error' => 'Failed to add project'], 500);
    }
});

// Updating a project
$app->put('/v1/projects/{id}', function (Request $request, Response $response, $args) use ($mysqli) {
    $id = $args['id'];
    $data = $request->getParsedBody();

    $name = $data['name'];
    $code = $data['code'];
    $description = $data['description'];

    $query = "UPDATE projects
                 SET name = ?,
                     code = ?,
                     description = ?
               WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("sssi", $name, $code, $description, $id);

    if ($stmt->execute()) {
        return jsonResponse($response, ['message' => 'Project updated successfully'], 200);
    } else {
        return jsonResponse($response, ['error' => 'Failed to update project'], 500);
    }
});

// Deleting a project
$app->delete('/v1/projects/{id}', function (Request $request, Response $response, $args) use ($mysqli) {
    $id = $args['id'];

    $query = "DELETE FROM projects
               WHERE id = ?";
    $stmt = $mysqli->prepare($query);

    // Check for errors in preparing the query
    if (!$stmt) {
        return jsonResponse($response, ['error' => 'Database prepare error: ' . $mysqli->error], 500);
    }

    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $stmt->close();
        return jsonResponse($response, ['message' => 'Project deleted successfully'], 200); // FIX ME, find better http response code (but not 204...)
    } else {
        $errorMsg = $stmt->error; // Capture the specific error
        $stmt->close();
        return jsonResponse($response, ['error' => 'Failed to delete project: ' . $errorMsg], 500);
    }
});

$app->options('/v1/projects/{id}', function (Request $request, Response $response, $args) use ($mysqli) {
    return $response;
});

// Get all employees
$app->get('/v1/employees', function (Request $request, Response $response) use ($mysqli) {
    $query = "SELECT *
                FROM employees
               ORDER BY ID";
    $result = $mysqli->query($query);

    $employees = [];

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $employees[] = $row;
        }
    }

    return jsonResponse($response, $employees, 200);
});

// Get a single employee
$app->get('/v1/employees/{id}', function (Request $request, Response $response, $args) use ($mysqli) {
    $id = $args['id'];
    $query = "SELECT *
                FROM employees
               WHERE id = ? ";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $employee = $result->fetch_assoc();

    if ($employee) {
        return jsonResponse($response, $employee, 200);
    } else {
        return jsonResponse($response, ['error' => 'Employee not found'], 404);
    }
});

// Add a new employee
$app->post('/v1/employees', function (Request $request, Response $response) use ($mysqli) {
    $data = $request->getParsedBody();

    $firstName = $data['first_name'];
    $lastName = $data['last_name'];
    $specialization = $data['specialization'];

    $query = "INSERT INTO employees (first_name,
                                     last_name,
                                     specialization)
              VALUES (?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("sss", $firstName, $lastName, $specialization);

    if ($stmt->execute()) {
        return jsonResponse($response, ['message' => 'Employee added successfully'], 200);
    } else {
        return jsonResponse($response, ['error' => 'Failed to add employee'], 500);
    }
});

// Update an employee
$app->put('/v1/employees/{id}', function (Request $request, Response $response, $args) use ($mysqli) {
    $id = $args['id'];
    $data = $request->getParsedBody();

    $firstName = $data['first_name'];
    $lastName = $data['last_name'];
    $specialization = $data['specialization'];

    $query = "UPDATE employees SET first_name = ?,
                                   last_name = ?,
                                   specialization = ?
              WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("sssi", $firstName, $lastName, $specialization, $id);

    if ($stmt->execute()) {
        return jsonResponse($response, ['message' => 'Employee updated successfully'], 200);
    } else {
        return jsonResponse($response, ['error' => 'Failed to update employee'], 500);
    }
});

// Delete an employee
$app->delete('/v1/employees/{id}', function (Request $request, Response $response, $args) use ($mysqli) {
    $id = $args['id'];

    $query = "DELETE FROM employees
               WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        return jsonResponse($response, ['message' => 'Employee deleted successfully'], 200); // FIX ME, find better http response code (but not 204...)
    } else {
        return jsonResponse($response, ['error' => 'Failed to delete employee'], 500);
    }
});

// Run the Slim application
$app->run();