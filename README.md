# 1. Exercise RESTful API

## Demo
- [www.bennykraeckmans.be](https://www.bennykraeckmans.be/)
- [api.bennykraeckmans.be](https://api.bennykraeckmans.be/)

## API testing

``` sh
curl -L -X GET https://api.bennykraeckmans.be/v1/employees
```

``` sh
curl -L -X GET https://api.bennykraeckmans.be/v1/employees/1
```

``` sh
curl -L -X POST -H "Content-Type: application/json" \
-d '{"first_name":"Benny", "last_name":"Kraeckmans", "specialization":""}' \
https://api.bennykraeckmans.be/v1/employees
```

``` sh
curl -L -X PUT -H "Content-Type: application/json" \
-d '{"first_name":"Benny", "last_name":"Kraeckmans", "specialization":"Hi mom!"}' \
https://api.bennykraeckmans.be/v1/employees/420
```

``` sh
curl -L -X DELETE https://api.bennykraeckmans.be/v1/employees/420
```

## References

### Best practices
- [Mark Roland: How to Build a RESTful API Web Service with PHP](https://web.archive.org/web/20220209214153/https://markroland.com/portfolio/restful-php-api)
- [Corey Maynard: Creating a RESTful API with PHP](https://web.archive.org/web/20220314015154/http://coreymaynard.com/blog/creating-a-restful-api-with-php/)

### Slim Framework
- [Installation - Slim Framework](https://www.slimframework.com/docs/v4/start/installation.html)
- [Apache & mod_rewrite - Slim Framework](https://www.slimframework.com/docs/v4/start/web-servers.html#apache-configuration)
- [Routing - Slim Framework](https://www.slimframework.com/docs/v4/objects/routing.html#how-to-create-routes)
- [Setting up CORS - Slim Framework](https://www.slimframework.com/docs/v4/cookbook/enable-cors.html)