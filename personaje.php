<?php

// Interfaz Batalla con la función atacar()
interface Batalla {
    public function atacar($objetivo);
}

// Interfaz Mago para invocar
interface MagoInterface {
    public function invocar();
}

// Clase base Personaje con atributos heredables
class Personaje {
    protected $vida;
    protected $daño;
    protected $estamina;
    protected $nombre;

    public function __construct($nombre, $vida, $daño, $estamina) {
        $this->nombre = $nombre;
        $this->vida = $vida;
        $this->daño = $daño;
        $this->estamina = $estamina;
    }

    public function mostrarAtributos() {
        echo "Personaje: $this->nombre<br>";
        echo "Vida: $this->vida<br>";
        echo "Daño: $this->daño<br>";
        echo "Estamina: $this->estamina<br>";
    }

    public function calcularDaño() {
        $dado = rand(1, 6);  // Tirada del dado
        $multiplicador = $dado / 6; // Se calcula el porcentaje basado en el dado
        $dañoReal = $this->daño * $multiplicador;

        // Mostrar la imagen correspondiente al dado
        echo "Tirada del dado de ataque:<br> <img src='img/{$dado}.jpg' alt='Dado {$dado}'><br>";
        echo "Daño real causado: " . round($dañoReal, 2) . "<br>";

        return $dañoReal;
    }

    public function atacar($objetivo) {
        $daño = $this->calcularDaño();
        $objetivo->recibirDaño($daño, $this->nombre);
    }
}

// Clase Paladín
class Paladin extends Personaje implements Batalla {
    public function __construct() {
        parent::__construct('Paladín', 150, 30, 100);
    }
}

// Clase Mago
class Mago extends Personaje implements Batalla, MagoInterface {
    public function __construct() {
        parent::__construct('Mago', 100, 40, 80);
    }

    public function invocar() {
        echo "Mago invoca Dragon de fuego<br>";
    }
}

// Clase Caballero
class Caballero extends Personaje implements Batalla {
    public function __construct() {
        parent::__construct('Caballero', 200, 25, 120);
    }
}
?>
