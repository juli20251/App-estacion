<?php
session_start();

// Verificar sesión
if(!isset($_SESSION['usuario']) && !isset($_SESSION['user'])) {
    header("Location: index.php?url=login");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel de estaciones</title>
<style>
body { font-family: 'Segoe UI', sans-serif; background: linear-gradient(135deg,#667eea 0%,#764ba2 100%); padding-bottom:60px; }
h1 { text-align:center; color:white; margin-top:30px; }
#lista { display:flex; flex-wrap:wrap; justify-content:center; gap:20px; margin:30px; }
.btn-estacion { background:white; color:#333; border:none; padding:20px; border-radius:12px; cursor:pointer; width:280px; }
</style>
</head>
<body>
<h1>Panel de Estaciones</h1>
<div id="lista"></div>

<template id="tplEstacion">
  <button class="btn-estacion"></button>
</template>

<script>
async function cargarEstaciones() {
  try {
    const resp = await fetch("../datos.php"); // ruta a tu API de estaciones
    const estaciones = await resp.json();

    const lista = document.getElementById("lista");
    const tpl = document.getElementById("tplEstacion");

    estaciones.forEach(est => {
      const clone = tpl.content.cloneNode(true);
      const boton = clone.querySelector(".btn-estacion");
      boton.textContent = `${est.apodo} - ${est.ubicacion} (${est.visitas} visitas)`;

      // Abrir detalle
      boton.onclick = () => {
        window.location.href = `detalle.php?chipid=${est.chipid}`;
      };

      lista.appendChild(clone);
    });
  } catch(err) {
    console.error("Error al cargar estaciones:", err);
    document.getElementById("lista").innerHTML = "<p style='color:red;'>Error al cargar las estaciones</p>";
  }
}
cargarEstaciones();
</script>
</body>
</html>
