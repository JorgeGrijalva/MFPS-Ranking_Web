<!DOCTYPE html>
<html lang="es">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
    body {
        background-color: transparent;
        color: white;
        font-family: Arial, sans-serif;
    }

    table {
        border-collapse: collapse;
        width: 100%;
        max-width: 900px;
        margin: 0 auto;
        background-color: transparent;
    }

    th, td {
        border: 1px solid #EB0605;
        text-align: left;
        padding: 8px;
    }

    th {
        background-color: #EB0605;
    }

    tr:nth-child(even) {
        background-color: rgba(235, 6, 5, 0.2);
    }

    tr:nth-child(odd) {
        background-color: transparent;
    }

    .filter-dropdown {
        text-align: center;
        margin-bottom: 10px;
    }

    select, .filter-button {
        background-color: #EB0605;
        color: white;
        border: 1px solid #EB0605;
        padding: 5px 10px;
        margin: 5px;
        cursor: pointer;
        border-radius: 5px;
        text-decoration: none;
        display: inline-block;
    }

    select {
        width: 40%;
        max-width: 200px;
    }

    .filter-button {
        width: 20%;
    }

    /* Media query para dispositivos móviles */
    @media (max-width: 600px) {
        table {
            font-size: 14px; /* Reduce el tamaño de fuente para dispositivos móviles */
        }

        select, .filter-button {
            width: 100%;
            max-width: none;
        }
    }
</style>
</head>
<body>
<div class="filter-dropdown">
    <form method="GET">
        <select id="orderBy" name="orderBy">
            <option value="score" <?php if (isset($_GET['orderBy']) && $_GET['orderBy'] === 'score') echo 'selected'; ?>>Puntuación</option>
            <option value="kills" <?php if (isset($_GET['orderBy']) && $_GET['orderBy'] === 'kills') echo 'selected'; ?>>Asesinatos</option>
            <option value="deaths" <?php if (isset($_GET['orderBy']) && $_GET['orderBy'] === 'deaths') echo 'selected'; ?>>Muertes</option>
            <option value="playtime" <?php if (isset($_GET['orderBy']) && $_GET['orderBy'] === 'playtime') echo 'selected'; ?>>Tiempo de juego</option>
        </select>
        <a href="javascript:void(0);" class="filter-button" onclick="filterTable('asc')">Menor</a>
        <a href="javascript:void(0);" class="filter-button" onclick="filterTable('desc')">Mayor</a>
    </form>
</div>
<?php

// Author: Olympusoft C.25/11/21
// Copyright (c) 2023 Olympusoft.com
// License: free to use
// Stats WordPress for MFPS
// Requested XYZ PHP Code Plugin for work...
// Cambia los datos de tu base de datos en $con ...

$con = mysqli_connect("localhost", "User", "Password", "Database");
// Comprobar la conexión
if (mysqli_connect_errno()) {
    echo "Error al conectar a MySQL: " . mysqli_connect_error();
}

// Filtrado y ordenación
$orderBy = isset($_GET['orderBy']) ? $_GET['orderBy'] : 'score'; // Columna por la que se ordena
$ascDesc = isset($_GET['ascDesc']) ? $_GET['ascDesc'] : 'desc'; // Orden ascendente o descendente

$query = "SELECT * FROM bl_game_users ORDER BY $orderBy $ascDesc";
$result = mysqli_query($con, $query);

// Mostrar botones de filtrado
echo '<div class="filter-buttons">

</div>';

// Mostrar tabla
echo '<div style="overflow-x:auto;">';
echo "<table>
 <tr>
     <th>Ranking</th>
     <th>Jugadores</th>
     <th>Asesinatos</th>
     <th>Muertes</th>
     <th>Puntuación</th>
     <th>Tiempo de Juego Hrs</th>
     <th>Clan</th>
   </tr>";

$ranking = 1; // Inicializar el ranking
while ($row = mysqli_fetch_array($result)) {
    $seconds = $row['playtime'];
    $nap = preg_replace('/^00:/', '', gmdate("H:i:s", $seconds));
    echo "<tr>";
    echo "<td>$ranking</td>"; // Mostrar el ranking
    echo "<td>" . $row['nick'] . "</td>";
    echo "<td>" . $row['kills'] . "</td>";
    echo "<td>" . $row['deaths'] . "</td>";
    echo "<td>" . $row['score'] . "</td>";
    echo "<td>$nap</td>";

    // Actualizar la tabla de clanes
    $resulta = mysqli_query($con, "SELECT * FROM bl_game_clans WHERE id=" . $row['clan'] . "");
    if ($resulta->num_rows > 0) {
        while ($rowa = mysqli_fetch_array($resulta)) {
            echo "<td>" . $rowa['tag'] . "</td>";
        }
    } else {
        echo "<td>-</td>";
    }

    $ranking++; // Incrementar el ranking

    echo "</tr>";
}

echo "</table></div>";
mysqli_close($con);
?>
</body>
</html>

<script>
    function filterTable(order) {
        var orderBy = document.getElementById('orderBy').value;
        var ascDesc = order === 'asc' ? 'asc' : 'desc';
        location.href = '?orderBy=' + orderBy + '&ascDesc=' + ascDesc;
    }
</script>