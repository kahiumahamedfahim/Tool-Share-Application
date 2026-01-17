<?php

class Router
{
    public function run()
    {
        // default route
        $url = $_GET['url'] ?? 'user/guestView';

        $parts = explode('/', trim($url, '/'));

        $controllerName = ucfirst($parts[0]) . 'Controller';
        $methodName = $parts[1] ?? 'index';

        $controllerFile = __DIR__ . '/../controllers/' . $controllerName . '.php';

        if (!file_exists($controllerFile)) {
            die("Controller not found");
        }

        require_once $controllerFile;

        if (!class_exists($controllerName)) {
            die("Controller class missing");
        }

        $controller = new $controllerName();

        if (!method_exists($controller, $methodName)) {
            die("Method not found");
        }

        $controller->$methodName();
    }
}
