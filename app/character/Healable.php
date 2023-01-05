<?php

namespace App\Character;

interface Healable
{
    public function recover( int $points ): void;
}

