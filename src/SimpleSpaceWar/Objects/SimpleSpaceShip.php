<?php

namespace OtusDZ\Src\SimpleSpaceWar\Objects;

use OtusDZ\Src\SimpleSpaceWar\Data\SimpleVector;
use OtusDZ\Src\SimpleSpaceWar\Errors\Errors;
use OtusDZ\Src\SimpleSpaceWar\Moves\SimpleMovable;
use Exception;

class SimpleSpaceShip implements SimpleMovable
{
    public SimpleVector $position;

    public SimpleVector $velocity;

    /**
     * @throws Exception
     * @return SimpleVector
     */
    public function getPosition(): SimpleVector
    {
        if (empty($this->position)) {
            throw new Exception(
                Errors::$errorMsgs[Errors::POSITION_UNSET],
                Errors::POSITION_UNSET,
            );
        }

        return $this->position;
    }

    /**
     * @throws Exception
     * @return SimpleVector
     */
    public function getVelocity(): SimpleVector
    {
        if (empty($this->velocity)) {
            throw new Exception(
                Errors::$errorMsgs[Errors::VELOCITY_UNSET],
                Errors::VELOCITY_UNSET,
            );
        }

        return $this->velocity;
    }

    /**
     * @param SimpleVector $v
     */
    public function setPosition(SimpleVector $v)
    {
        $this->position = $v;
    }
}
