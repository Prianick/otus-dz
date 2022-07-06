<?php

namespace OtusDZ\Src\Dz5IoC;

use Closure;

class IoC
{
    public const IOC_REGISTER = 'IOC_REGISTER';
    public const SCOPE_NEW = 'Scopes.New';
    public const SCOPE_CURRENT = 'Scopes.Current';
    public const DEFAULT_SCOPE = 'Scopes.Default';

    /** @var array */
    public static array $scopes;

    /** @var string */
    protected static string $currentScope = self::DEFAULT_SCOPE;

    /**
     * Метод регистрирует/разрешает зависимости.
     * Реализованы скоупы.
     *
     * @param string $entityName
     * @param array|string $args
     * @param Closure|null $function
     * @return IoCResolveCommand|void
     */
    public static function resolve(string $entityName, $args = [], ?Closure $function = null)
    {
        if (self::IOC_REGISTER === $entityName && !empty($function)) {
            return new IoCResolveCommand(static function () use ($args, $function) {
                if ($args === self::IOC_REGISTER) {
                    self::$scopes[self::$currentScope] = $function();
                } else {
                    self::$scopes[self::$currentScope]->set($args, $function);
                }
            });
        } elseif (in_array($entityName, [self::SCOPE_NEW, self::SCOPE_CURRENT], true)) {
            IoC::$currentScope = $args;
        } else {
            return self::$scopes[self::$currentScope]->get($entityName, $args);
        }
    }

    /**
     * @return string
     */
    public static function getCurrentScope(): string
    {
        return self::$currentScope;
    }
}
