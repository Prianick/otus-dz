<?php

namespace OtusDZ\Src\SimpleSpaceWar\Moves;

use Exception;

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
        $this->m->setPosition(
            $this->m->getPosition()->plus($this->m->getVelocity())
        );
    }
}
