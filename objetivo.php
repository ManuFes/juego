<?php

// Clase Objetivo con atributos de vida y función para recibir daño
class Objetivo {
    public $nombre;
    public $vida;

    public function __construct($nombre, $vida) {
        $this->nombre = $nombre;
        $this->vida = $vida;
    }

    public function mostrarAtributos() {
        echo "Objetivo: $this->nombre<br>";
        echo "Vida restante: $this->vida<br>";
    }

    public function calcularDefensa() {
        $dado = rand(1, 6);  // Tirada del dado de defensa
        $reduccion = $dado / 6; // Se calcula el porcentaje de reducción de daño

        // Mostrar la imagen correspondiente al dado de defensa
        echo "Tirada del dado de defensa:<br> <img src='img/{$dado}.jpg' alt='Dado de defensa {$dado}'><br>";
        echo "Reducción de daño: " . round($reduccion * 100, 2) . "%<br>";
        return $reduccion;
    }

    public function recibirDaño($daño, $nombreAtacante) {
        $reduccion = $this->calcularDefensa();
        $dañoReducido = $daño * (1 - $reduccion); // Daño final después de la reducción
        $this->vida -= $dañoReducido;

        if ($this->vida <= 0) {
            echo "$nombreAtacante ha derrotado al $this->nombre<br>";
            $this->vida = 0;
        } else {
            echo "$this->nombre ha recibido $dañoReducido de daño después de la defensa<br>";
            $this->mostrarAtributos();
        }
    }
}
?>
