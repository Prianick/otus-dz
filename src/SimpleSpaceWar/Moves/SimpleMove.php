<?php

namespace OtusDZ\Src\SimpleSpaceWar\Moves;

use Exception;
use OtusDZ\Src\SimpleSpaceWar\Errors\Errors;

class SimpleMove
{
    public SimpleMovable $m;

    public function move(SimpleMovable $m): self
    {
        $this->m = $m;

        return $this;
    }

    /**
     * @throws Exception
     */
    public function execute()
    {
        if (empty($this->m->getVelocity())) {
            throw new Exception(
                Errors::$errorMsgs[Errors::VELOCITY_UNSET],
                Errors::VELOCITY_UNSET,
            );
        }
        if (empty($this->m->getPosition())) {
            throw new Exception(
                Errors::$errorMsgs[Errors::POSITION_UNSET],
                Errors::POSITION_UNSET,
            );
        }
        $this->m->setPosition(
            $this->m->getPosition()->plus($this->m->getVelocity())
        );
    }
}
