<?php

return [
    'Tank.move' => function ($objectId) {
        /**
         * TODO:
         * Все эти потроха можно вынести в отдельный класс, который по названию
         * Команды и айди объект генерировать команду.
         * Туда же спрятать рефлексию, по средствам которой будет генерироваться соответствующий адаптер.
         */
        /** @var \OtusDZ\Src\Dz8Messaging\ObjectsPool $objectPool */
        $objectPool = \OtusDZ\Src\Dz5IoC\IoC::resolve(\OtusDZ\Src\Dz8Messaging\ObjectsPool::class);
        $uObject = $objectPool->getObject($objectId);
        $adaptedUObject = \OtusDZ\Src\Dz6AdapterGen\AdapterCreator::create(
            \OtusDZ\Src\Dz2MoveAndRotate\Moves\Movable::class, /* Здесь можно добавить рефлексию */
            $uObject
        );

        return new \OtusDZ\Src\Dz4MacroCommand\Commands\MoveCommand($adaptedUObject);
    },
];

