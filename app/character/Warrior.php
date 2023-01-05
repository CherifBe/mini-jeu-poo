<?php


namespace App\Character;

use \Exception;

use App\App;
use App\DiceResult;

class Warrior extends Character implements Healable
{
    protected const BASE_AGILITY = 70;
    protected const BASE_ARMOR = 120;
    protected const BASE_HP = 150;
    protected const BASE_SPEED = 50;
    protected const BASE_STAMINA = 150;
    protected const BASE_STRENGTH = 180;

    public function attack(Character $defender): ?Character
    {

        $attack_result = App::rollDice();

        switch ($attack_result) {
            case DiceResult::SUCCESS:
                // TODO: HTML
                return $this->processAttack($defender);

            case DiceResult::CRITICAL:
                // TODO: HTML
                return $this->processAttack($defender, 2);

            case DiceResult::FAILURE:
                $defender->block();
                return null;

            default:
                throw new Exception('Le dé est cassé :\'(');
        }
    }

    // Il ne se passe rien
    public function block()
    {
        // echo $this->name .' s\'exclame: "Hehe tu m\'as loupé, et en plus j\'ai ton portefeuille :P"';
        // echo '<br>';
    }

    protected function processAttack(Character $defender, int $multiply = 1): ?Character
    {
        $delta = $this->strength - $defender->armor;
        $half_strength = ceil($this->strength / 2);
        $damage = ($delta > $half_strength ? $delta : $half_strength) * $multiply; // Coup critique
        $hp_result = $defender->hp - $damage;

        if ($hp_result <= 0) return $this; // Retourne l'attaquant

        $defender->hp = $hp_result;

        return null;
    }

    public function recover( int $points ): void
    {
        // TODO: A FAIRE
    }

}
