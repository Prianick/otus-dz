<?php

namespace OtusDZ\Src\Dz9ServiceCreation;

use OtusDZ\Src\Dz5IoC\IoC;

class App
{
    public RouterFunctions $router;

    public RequestFunctions $request;

    private static App $instance;

    private function __construct()
    {
        $this->router = IoC::resolve(RouterFunctions::class);
        $this->request = IoC::resolve(RequestFunctions::class);
    }

    /**
     * @return static
     */
    public static function getInstance(): self
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param RequestFunctions $request
     */
    public function run(RequestFunctions $request)
    {
        echo json_encode($this->router->dispatch($request));
    }
}
