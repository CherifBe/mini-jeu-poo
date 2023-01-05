<?php

namespace App\Character;

abstract class Character
{
    protected string $name;

    public function getName(): string
    {
        return $this->name;
    }

    public int $agility;
    public int $armor;
    public int $hp;
    public int $speed;
    public int $stamina;
    public int $strength;

    public function __construct(string $name)
    {
        $class = get_called_class();

        $this->name = $name;
        $this->agility = self::calcStat($class::BASE_AGILITY);
        $this->armor = self::calcStat($class::BASE_ARMOR);
        $this->hp = self::calcStat($class::BASE_HP);
        $this->speed = self::calcStat($class::BASE_SPEED);
        $this->stamina = self::calcStat($class::BASE_STAMINA);
        $this->strength = self::calcStat($class::BASE_STRENGTH);
    }

    abstract public function attack(Character $defender);

    abstract public function block();

    protected static function calcStat( int $base_value ): int
    {
        return rand( 0, 20 ) + $base_value;
    }
    public function __toString(): string
    {
        return $this->name;
    }
}

