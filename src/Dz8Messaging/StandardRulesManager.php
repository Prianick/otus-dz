<?php

namespace OtusDZ\Src\Dz8Messaging;

use Closure;
use OtusDZ\Src\Dz4MacroCommand\Commands\Command;
use OtusDZ\Src\Dz5IoC\IoC;
use OtusDZ\Src\Dz5IoC\IoCRegister;

class StandardRulesManager implements RulesManager
{
    protected string $confDir = __DIR__ . DIRECTORY_SEPARATOR . 'configs';

    /**
     * @param string|null $confDir
     */
    public function __construct(?string $confDir = null)
    {
        if (!empty($confDir)) {
            $this->confDir = $confDir;
        }
        $files = scandir($this->confDir);
        $currentScope = IoC::getCurrentScope();
        foreach ($files as $fileName) {
            if (preg_match('`([\w.\-]+)\.conf\.php$`', $fileName, $matches) === 1) {
                $playerType = $matches[1];
                IoC::resolve(IoC::SCOPE_NEW, $playerType);
                if (empty(IoC::$scopes[$playerType])) {
                    IoC::resolve(IoC::IOC_REGISTER, IoC::IOC_REGISTER, static function () {
                        return new IoCRegister();
                    })->execute();
                }
                $config = include_once $this->confDir . DIRECTORY_SEPARATOR . $fileName;
                /**
                 * @var string $operationAlias
                 * @var Closure $resolveClosure
                 */
                foreach ($config as $operationAlias => $resolveClosure) {
                    IoC::resolve(IoC::IOC_REGISTER, $operationAlias, $resolveClosure)->execute();
                }
            }
        }
        IoC::resolve(IoC::SCOPE_CURRENT, $currentScope);
    }

    /**
     * @param string $playerType
     * @param string $operationAlias
     * @param array $args
     * @return Command
     */
    public function getCommand(string $playerType, string $operationAlias, array $args): Command
    {
        $currentScope = IoC::getCurrentScope();
        IoC::resolve(IoC::SCOPE_NEW, $playerType);
        $command = IoC::resolve($operationAlias, $args);
        IoC::resolve(IoC::SCOPE_CURRENT, $currentScope);

        return $command;
    }
}
