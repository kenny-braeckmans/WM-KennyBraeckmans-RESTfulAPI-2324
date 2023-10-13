<?php

/**
 * API for the project management app
 */

// Enable error reporting - REMOVE ME
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include required files
// Load Composer's autoloader for required libraries
require __DIR__ . '/vendor/autoload.php';

// Load the database configuration for the application
require __DIR__ . '/inc/config.php';

// Import necessary classes using the Composer autoloader
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteContext;

// Create a new Slim app
$app = AppFactory::create();

/**
 * Middleware
 */

 // This middleware will parse the JSON request body and add the parsed body to the request object
$app->addBodyParsingMiddleware();

// This middleware will add CORS headers to the response
$app->add(function (Request $request, RequestHandlerInterface $handler): Response {
    $routeContext = RouteContext::fromRequest($request);
    $routingResults = $routeContext->getRoutingResults();
    $methods = $routingResults->getAllowedMethods();
    $requestHeaders = $request->getHeaderLine('Access-Control-Request-Headers');

    $response = $handler->handle($request);

    $response = $response->withHeader('Access-Control-Allow-Origin', 'https://www.bennykraeckmans.be');
    $response = $response->withHeader('Access-Control-Allow-Methods', implode(',', $methods));
    $response = $response->withHeader('Access-Control-Allow-Headers', $requestHeaders);

    return $response;
});

// The RoutingMiddleware should be added after our CORS middleware so routing is performed first
$app->addRoutingMiddleware();

/**
 * Routes for API endpoints
 */

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
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
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
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    } else {
        $response->getBody()->write(json_encode(['error' => 'Project not found']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
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
        $response->getBody()->write(json_encode(['message' => 'Project added successfully']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    } else {
        $response->getBody()->write(json_encode(['error' => 'Failed to add project']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
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
        $response->getBody()->write(json_encode(['message' => 'Project updated successfully']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    } else {
        $response->getBody()->write(json_encode(['error' => 'Failed to update project']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
});

// Deleting a project
$app->delete('/v1/projects/{id}', function (Request $request, Response $response, $args) use ($mysqli) {
    $id = $args['id'];

    $stmt = $mysqli->prepare("DELETE FROM projects WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $response->getBody()->write(json_encode(['message' => 'Project deleted successfully']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    } else {
        $response->getBody()->write(json_encode(['error' => 'Failed to delete project']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
});

// Get all employees
$app->get('/v1/employees', function (Request $request, Response $response) use ($mysqli) {
    $sql = "SELECT * FROM employees";
    $result = $mysqli->query($sql);

    $employees = [];

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $employees[] = $row;
        }
    }

    $response->getBody()->write(json_encode($employees, JSON_PRETTY_PRINT));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
});

// Get a single employee
$app->get('/v1/employees/{id}', function (Request $request, Response $response, $args) use ($mysqli) {
    $id = $args['id'];
    $stmt = $mysqli->prepare("SELECT * FROM employees WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $employee = $result->fetch_assoc();

    if ($employee) {
        $response->getBody()->write(json_encode($employee, JSON_PRETTY_PRINT));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    } else {
        $response->getBody()->write(json_encode(['error' => 'Employee not found']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
    }
});

// Add a new employee
$app->post('/v1/employees', function (Request $request, Response $response) use ($mysqli) {
    $data = $request->getParsedBody();

    $firstName = $data['first_name'];
    $lastName = $data['last_name'];
    $specialization = $data['specialization'];

    $stmt = $mysqli->prepare("INSERT INTO employees (first_name, last_name, specialization) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $firstName, $lastName, $specialization);

    if ($stmt->execute()) {
        $response->getBody()->write(json_encode(['message' => 'Employee added successfully']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    } else {
        $response->getBody()->write(json_encode(['error' => 'Failed to add employee']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
});

// Update an employee
$app->put('/v1/employees/{id}', function (Request $request, Response $response, $args) use ($mysqli) {
    $id = $args['id'];
    $data = $request->getParsedBody();

    $firstName = $data['first_name'];
    $lastName = $data['last_name'];
    $specialization = $data['specialization'];

    $stmt = $mysqli->prepare("UPDATE employees SET first_name = ?, last_name = ?, specialization = ? WHERE id = ?");
    $stmt->bind_param("sssi", $firstName, $lastName, $specialization, $id);

    if ($stmt->execute()) {
        $response->getBody()->write(json_encode(['message' => 'Employee updated successfully']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    } else {
        $response->getBody()->write(json_encode(['error' => 'Failed to update employee']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
});

// Delete an employee
$app->delete('/v1/employees/{id}', function (Request $request, Response $response, $args) use ($mysqli) {
    $id = $args['id'];

    $stmt = $mysqli->prepare("DELETE FROM employees WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $response->getBody()->write(json_encode(['message' => 'Employee deleted successfully']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    } else {
        $response->getBody()->write(json_encode(['error' => 'Failed to delete employee']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
});

// Run the Slim application
$app->run();