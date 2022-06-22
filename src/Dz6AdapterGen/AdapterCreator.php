<?php

namespace OtusDZ\Src\Dz6AdapterGen;

use OtusDZ\Src\Dz2MoveAndRotate\Objects\UObject;
use OtusDZ\Src\Dz5IoC\IoC;
use ReflectionClass;

class AdapterCreator
{
    /**
     * @param string $interface
     * @param UObject|null $uObject
     * @throws \ReflectionException
     * @return mixed
     */
    public static function create(string $interface, ?UObject $uObject = null)
    {
        $rInterface = new ReflectionClass($interface);
        $adapterName = $rInterface->getShortName() . 'Adapter';
        if (class_exists($adapterName)) {
            return new $adapterName($uObject);
        }
        $methodsList = [];
        $methods = $rInterface->getMethods();
        foreach ($methods as $method) {
            $name = $method->getName();
            $params = $method->getParameters();
            $returnType = $method->getReturnType();
            $paramTypeNameList = [];
            $paramNameList = [];
            foreach ($params as $param) {
                $paramTypeNameList[] = '\\' . $param->getType() . ' $' . $param->getName();
                $paramNameList[] = '$' . $param->getName();
            }
            $propertyName = lcfirst(str_replace(['get', 'set'], '', $name));
            $IoCClass = IoC::class;
            if (!str_contains($name, 'get')) {
                $paramStr = implode(', ', array_merge(["\$this->obj"], $paramNameList));
                $body = "return \\{$IoCClass}::resolve(\"Tank.Operations.IMovable:{$propertyName}.set\", [{$paramStr}]);";

                // $body = "return \$this->obj->setProperty('" . $propertyName . "', \$" . $param->getName() . ");";
            } else {
                $body = "return \\{$IoCClass}::resolve(\"Tank.Operations.IMovable:{$propertyName}.get\", [\$this->obj]);";

                // $body = "return \$this->obj->getProperty('" . $propertyName . "');";
            }

            $returnTypeStr = '';
            if (!empty($returnType)) {
                $returnTypeStr = ':\\' . $returnType->getName();
            }

            $methodsList[] = " public function {$name}(" . implode(', ', $paramTypeNameList) . "){$returnTypeStr}
            {
                " . $body . "
            } ";
        }
        $code = "
            class {$adapterName} implements \\{$interface} {
            
                public  \$obj;
            
                public function __construct(\\" . UObject::class . " \$obj)
                {
                    \$this->obj = \$obj;
                }
                " . implode("\n", $methodsList) . "
            }
            ";

        eval($code);

        return new $adapterName($uObject);
    }
}

