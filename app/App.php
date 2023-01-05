<?php

namespace App;

use App\Character\Character;
use App\Character\Warrior;

class App
{
    private static ?self $instance = null;
    private int $currentTurn = 0;
    private ?Character $winner = null;
    private Character $attacker;
    private Character $defender;

    public static function getApp(): self
    {
        if( is_null( self::$instance ) ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function start(): void
    {
        echo '<h1>Le Choc des Barbares</h1>';

        $fighter1 = new Warrior( 'Conan Le Barbare' );
        $fighter2 = new Warrior( 'Kron Le Destructeur Ultime' );

        echo '<p><strong>'. $fighter1 .'</strong> VS <strong>'. $fighter2 .'</strong></p>';

        // Affectation selon la vitesse du premier attaquant
        if( $fighter1->speed >= $fighter2->speed ) {
            $this->attacker = $fighter1;
            $this->defender = $fighter2;
        }
        else {
            $this->attacker = $fighter2;
            $this->defender = $fighter1;
        }

        // Lancement de la partie
        while( is_null( $this->winner ) ) {
            $this->renderTurn();
        }

        echo '<h2 style="background-color:red">'. $this->winner .' A GAGNÉ LE COMBAT !!!</h2>';
    }

    public static function rollDice( int $bias = 0 ): int
    {
        /*
         * Aléatoire de 0 à 100
         * - 0 -> 44: Echec ( a < 45 )
         * - 45 -> 55: Critique ( a > 44 ET a < 55 )
         * - 56 -> 100: Succès ( a > 55 )
         */
        $alea = rand(0, 100) + $bias;

        if( $alea < 45 ) return DiceResult::FAILURE;

        if( $alea < 56 ) return DiceResult::CRITICAL;

        return DiceResult::SUCCESS;
    }

    private function renderTurn(): void
    {
        $this->currentTurn ++;

        $html_result =	'<div style="margin-bottom:20px">';
        $html_result .=		'<h2>Tour '. $this->currentTurn .'</h2>';
        $html_result .=		'<h3>'. $this->attacker .' attaque !</h3>';

        // Premier attaquant
        $this->winner = $this->attacker->attack( $this->defender );

        // Si dès l'attaque la victoire est obtenue, on termine le HTML et on sort
        if( !is_null( $this->winner ) ) {
            $html_result .= '<h3>'. $this->defender .' est K.O.</h3>';
            $html_result .=	'</div>';

            echo $html_result;
            return;
        }

        $html_result .= '<h3>'. $this->defender .' résiste ! Reste '. $this->defender->hp .'HP</h3>';

        // Inversion des opposants
        $this->switchOpponents();

        // Réplique du défenseur
        $html_result .=		'<h3>'. $this->attacker .' attaque !</h3>';

        $this->winner = $this->attacker->attack( $this->defender );

        // Si dès l'attaque la victoire est obtenue, on termine le HTML et on sort
        if( !is_null( $this->winner ) ) {
            $html_result .= '<h3>'. $this->defender .' est K.O.</h3>';
            $html_result .=	'</div>';

            echo $html_result;
            return;
        }

        $html_result .= '<h3>'. $this->defender .' résiste ! Reste '. $this->defender->hp .'HP</h3>';
        $html_result .=	'</div>';
        echo $html_result;
    }

    private function switchOpponents(): void
    {
        $attacker = $this->defender;
        $defender = $this->attacker;

        $this->attacker = $attacker;
        $this->defender = $defender;
    }
}

