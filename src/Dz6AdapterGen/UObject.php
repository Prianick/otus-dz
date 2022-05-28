<?php

interface UObject
{
    public function setProperty($name, object $value);

    public function getProperty($name);
}
