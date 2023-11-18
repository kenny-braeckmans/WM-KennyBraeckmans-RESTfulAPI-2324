# 1. Exercise RESTful API

## 🔗 Demo

- 🌐 Web: [www.bennykraeckmans.be](https://www.bennykraeckmans.be/)
- 📡 API: [api.bennykraeckmans.be](https://api.bennykraeckmans.be/)

## Roadmap/Issues

### Backend
- [x] Configure MySQL database/tables
- [x] Implement SLIM framework for API
- [x] Implement [tuupola/cors-middleware: PSR-7 and PSR-15 CORS middleware](https://github.com/tuupola/cors-middleware)
- [ ] Implement [ErrorMiddleware](https://www.slimframework.com/docs/v4/middleware/error-handling.html)
- [ ] Add endpoints for adding and removing projectmembers

### Frontend
- [x] Implement Bootstrap for webapp
- [ ] Add forms for adding and editing projects and employees

## 📚 References

### Best practices

- [Mark Roland: How to Build a RESTful API Web Service with PHP](https://web.archive.org/web/20220209214153/https://markroland.com/portfolio/restful-php-api)
- [Corey Maynard: Creating a RESTful API with PHP](https://web.archive.org/web/20220314015154/http://coreymaynard.com/blog/creating-a-restful-api-with-php/)

### Slim Framework

- [Installation - Slim Framework](https://www.slimframework.com/docs/v4/start/installation.html)
- [Apache & mod_rewrite - Slim Framework](https://www.slimframework.com/docs/v4/start/web-servers.html#apache-configuration)
- [Routing - Slim Framework](https://www.slimframework.com/docs/v4/objects/routing.html#how-to-create-routes)
- [Body Parsing Middleware - Slim Framework](https://www.slimframework.com/docs/v4/middleware/body-parsing.html)
- [Setting up CORS - Slim Framework](https://www.slimframework.com/docs/v4/cookbook/enable-cors.html)
- [How to Create a RESTful API With Slim 4, PHP and MySQL](https://www.twilio.com/blog/create-restful-api-slim4-php-mysql)

### Ionic

- [CORS Errors: Cross-Origin Resource Sharing - Ionic Documentation](https://ionicframework.com/docs/troubleshooting/cors)

### Bootstrap

- [Tables · Bootstrap v5.3](https://getbootstrap.com/docs/5.3/content/tables/)

## Development

Configure or restore tables in MySQL database
```
mysql> source ./tmp/setup.sql
```

## 🧪 API Testing

### Projects

GET all projects
``` sh
curl -L -X GET https://api.bennykraeckmans.be/v1/projects
```

GET project with id 1
``` sh
curl -L -X GET https://api.bennykraeckmans.be/v1/projects/1
```

POST new project
``` sh
curl -L -X POST -H "Content-Type: application/json" \
-d '{"name":"ProjectName", "code":"ProjectCode", "description":"DescriptionHere"}' \
https://api.bennykraeckmans.be/v1/projects
```

PUT updated project with id 420
``` sh
curl -L -X PUT -H "Content-Type: application/json" \
-d '{"name":"ProjectName", "code":"ProjectCode", "description":"Hi mom!"}' \
https://api.bennykraeckmans.be/v1/projects/420
```

DELETE project with id 420
``` sh
curl -L -X DELETE https://api.bennykraeckmans.be/v1/projects/420
```

### Employees

GET all employees
``` sh
curl -L -X GET https://api.bennykraeckmans.be/v1/employees
```

GET employee with id 1
``` sh
curl -L -X GET https://api.bennykraeckmans.be/v1/employees/1
```

POST new employee
``` sh
curl -L -X POST -H "Content-Type: application/json" \
-d '{"first_name":"Benny", "last_name":"Kraeckmans", "specialization":""}' \
https://api.bennykraeckmans.be/v1/employees
```

PUT updated employee with id 420
``` sh
curl -L -X PUT -H "Content-Type: application/json" \
-d '{"first_name":"Benny", "last_name":"Kraeckmans", "specialization":"Hi mom!"}' \
https://api.bennykraeckmans.be/v1/employees/420
```

DELETE employee with id 420
``` sh
curl -L -X DELETE https://api.bennykraeckmans.be/v1/employees/420
```