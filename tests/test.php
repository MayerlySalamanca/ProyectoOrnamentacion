<?php
require "..\app\Models\MateriaPrima.php";

use App\Models\MateriaPrima;

$usuario = MateriaPrima::searchForId(1);
$usuario->setNombre('Varilla');
var_dump($usuario);
if($usuario->update()){
    echo "Bien";
}else{
    echo "No";
}