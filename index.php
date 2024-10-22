<?php
require_once 'personaje.php';
require_once 'objetivo.php';

session_start(); // Iniciar la sesión después de cargar las clases

// Función para inicializar los personajes y objetivos
function inicializarPartida() {
    $_SESSION['objetivos'] = [
        'Goblin' => new Objetivo('Goblin', 50),
        'Troll' => new Objetivo('Troll', 100),
        'Gigante' => new Objetivo('Gigante', 150),
    ];

    unset($_SESSION['ultimo_personaje']);
    unset($_SESSION['ultimo_objetivo']);
}

// Inicializar la partida si aún no se ha hecho
if (!isset($_SESSION['objetivos'])) {
    inicializarPartida();
}

// Variables para mostrar estadísticas y resultados
$mostrarEstadisticasPersonaje = false;
$mostrarEstadisticasObjetivo = false;
$objetivo = null;
$nombrePersonaje = "Personaje"; // Valor por defecto
$nombreObjetivo = "Objetivo";   // Valor por defecto
$resultadoAccion = ""; // Variable para almacenar los resultados de la acción

// Procesar la selección del personaje y la acción
if (isset($_POST['personaje']) && isset($_POST['boton'])) {
    $personajeSeleccionado = $_POST['personaje'];
    $botonPulsado = $_POST['boton'];
    $objetivoSeleccionado = $_POST['objetivo'] ?? null;

    // Guardar los valores seleccionados para no perderlos tras enviar el formulario
    $_SESSION['ultimo_personaje'] = $personajeSeleccionado;
    $_SESSION['ultimo_objetivo'] = $objetivoSeleccionado;

    // Asignar el nombre del personaje seleccionado
    switch ($personajeSeleccionado) {
        case 'Paladin':
            $personaje = new Paladin();
            $nombrePersonaje = 'Paladín';
            break;
        case 'Mago':
            $personaje = new Mago();
            $nombrePersonaje = 'Mago';
            break;
        case 'Caballero':
            $personaje = new Caballero();
            $nombrePersonaje = 'Caballero';
            break;
        default:
            echo "Error: Personaje no válido.<br>";
            break;
    }

    // Asignar el nombre del objetivo seleccionado
    if ($objetivoSeleccionado !== null) {
        $objetivo = $_SESSION['objetivos'][$objetivoSeleccionado];
        $nombreObjetivo = $objetivo->nombre;
    }

    // No hacer ninguna acción si el botón "Seleccionar" fue pulsado
    if ($botonPulsado === 'seleccionar') {
        // Simplemente recargar la página con las nuevas selecciones y actualizar los nombres en los botones.
    } else {
        // Ejecutar acción según el botón pulsado
        if ($personaje !== null) {
            if ($botonPulsado === 'accion' && $objetivoSeleccionado !== null) {
                ob_start(); // Capturar salida
                $personaje->atacar($objetivo);
                $resultadoAccion = ob_get_clean(); // Guardar el resultado de la acción

                // Actualizar el objetivo en la sesión después del ataque
                $_SESSION['objetivos'][$objetivoSeleccionado] = $objetivo;

            } elseif ($botonPulsado === 'estadisticas_personaje') {
                ob_start(); // Capturar salida
                $personaje->mostrarAtributos();
                $resultadoAccion = ob_get_clean();
                $mostrarEstadisticasPersonaje = true;
            } elseif ($botonPulsado === 'estadisticas_objetivo' && $objetivoSeleccionado !== null) {
                ob_start(); // Capturar salida
                $objetivo->mostrarAtributos();
                $resultadoAccion = ob_get_clean();
                $mostrarEstadisticasObjetivo = true;
            } elseif ($botonPulsado === 'reiniciar') {
                inicializarPartida();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Juego de Personajes</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
        }

        h1, h2 {
            text-align: center;
            color: #333;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .character-selection, .objective-selection {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 20px;
        }

        .character-selection img, .objective-selection img {
            width: 150px;
            height: auto;
            cursor: pointer;
            border: 2px solid transparent;
            transition: border 0.3s;
        }

        input[type="radio"] {
            display: none;
        }

        input[type="radio"]:checked + img {
            border: 2px solid #00f;
        }

        .actions {
            margin-bottom: 20px;
        }

        .buttons {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .result-container {
            width: 100%;
            max-width: 800px;
            margin-top: 20px;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .result {
            text-align: center;
        }
    </style>
</head>

<body>
    <h1>Selecciona un Personaje</h1>
    <div class="container">
        <form method="POST" action="">
            <!-- Selección de personajes -->
            <div class="character-selection">
                <label>
                    <input type="radio" name="personaje" value="Paladin" <?= isset($_SESSION['ultimo_personaje']) && $_SESSION['ultimo_personaje'] == 'Paladin' ? 'checked' : '' ?>>
                    <img src="img/paladin.jpg" alt="Paladín">
                </label>
                <label>
                    <input type="radio" name="personaje" value="Mago" <?= isset($_SESSION['ultimo_personaje']) && $_SESSION['ultimo_personaje'] == 'Mago' ? 'checked' : '' ?>>
                    <img src="img/mago.jpg" alt="Mago">
                </label>
                <label>
                    <input type="radio" name="personaje" value="Caballero" <?= isset($_SESSION['ultimo_personaje']) && $_SESSION['ultimo_personaje'] == 'Caballero' ? 'checked' : '' ?>>
                    <img src="img/caballero.jpg" alt="Caballero">
                </label>
            </div>

            <h2>Selecciona un Objetivo</h2>
            <!-- Selección de objetivos -->
            <div class="objective-selection">
                <label>
                    <input type="radio" name="objetivo" value="Goblin" <?= isset($_SESSION['ultimo_objetivo']) && $_SESSION['ultimo_objetivo'] == 'Goblin' ? 'checked' : '' ?>>
                    <img src="img/goblin.jpg" alt="Goblin">
                </label>
                <label>
                    <input type="radio" name="objetivo" value="Troll" <?= isset($_SESSION['ultimo_objetivo']) && $_SESSION['ultimo_objetivo'] == 'Troll' ? 'checked' : '' ?>>
                    <img src="img/troll.jpg" alt="Troll">
                </label>
                <label>
                    <input type="radio" name="objetivo" value="Gigante" <?= isset($_SESSION['ultimo_objetivo']) && $_SESSION['ultimo_objetivo'] == 'Gigante' ? 'checked' : '' ?>>
                    <img src="img/gigante.jpg" alt="Gigante">
                </label>
            </div>

            <!-- Botones -->
            <div class="buttons">
                <button type="submit" name="boton" value="seleccionar">Seleccionar</button>
                <button type="submit" name="boton" value="accion"><?= $nombrePersonaje ?> realiza acción contra <?= $nombreObjetivo ?></button>
                <button type="submit" name="boton" value="estadisticas_personaje">Mostrar Estadísticas de <?= $nombrePersonaje ?></button>
                <button type="submit" name="boton" value="estadisticas_objetivo">Mostrar Estadísticas de <?= $nombreObjetivo ?></button>
                <button type="submit" name="boton" value="reiniciar">Reiniciar Partida</button>
            </div>
        </form>

        <!-- Resultados y estadísticas -->
        <div class="result-container">
            <div class="result">
                <?= $resultadoAccion ?>
            </div>
        </div>
    </div>
</body>

</html>
