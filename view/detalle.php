<?php $chipid = $_GET['chipid'] ?? ''; ?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Detalle estación <?= htmlspecialchars($chipid) ?></title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
body { font-family:'Segoe UI'; background:linear-gradient(135deg,#3c1053,#ad5389); color:white; text-align:center; margin:0; }
header { padding:20px; font-size:22px; font-weight:bold; background:rgba(0,0,0,0.3); }
.info { margin-top:15px; font-size:16px; }
.contenedor { display:grid; grid-template-columns:repeat(auto-fit,minmax(300px,1fr)); gap:20px; padding:30px; }
.card { background: rgba(255,255,255,0.1); border-radius:12px; padding:20px; }
canvas { max-width:100%; height:250px; }
button { background:#ff4081; border:none; padding:10px 20px; border-radius:8px; color:white; cursor:pointer; margin-top:10px; }
button:hover { background:#ff609d; }
</style>
</head>
<body>
<header>🌦 Detalle estación <?= htmlspecialchars($chipid) ?></header>
<div class="info" id="info">Cargando datos...</div>

<div class="contenedor">
  <div class="card"><h3>Temperatura (°C)</h3><canvas id="grafTemp"></canvas></div>
  <div class="card"><h3>Humedad (%)</h3><canvas id="grafHum"></canvas></div>
  <div class="card"><h3>Viento (Km/h)</h3><canvas id="grafViento"></canvas></div>
  <div class="card"><h3>Presión (hPa)</h3><canvas id="grafPresion"></canvas></div>
  <div class="card"><h3>Riesgo de Incendio</h3><canvas id="grafIncendio"></canvas></div>
</div>

<button onclick="location.href='/alumno/10014/app-estacion/view/panel.php'">← Volver al panel</button>

<script>
const chipid = "<?= $chipid ?>";

async function cargarDatos() {
  try {
    const resp = await fetch(`../api/datos_estacion.php?chipid=${chipid}&cant=7`);


    const data = await resp.json();

    if(!Array.isArray(data)) {
      document.getElementById('info').textContent = "Error al obtener datos";
      return;
    }

    const fechas = data.map(d => d.fecha_hora || 'N/A');
    const temperatura = data.map(d => parseFloat(d.temperatura) || 0);
    const humedad = data.map(d => parseFloat(d.humedad) || 0);
    const viento = data.map(d => parseFloat(d.viento) || 0);
    const presion = data.map(d => parseFloat(d.presion) || 0);
    const incendio = data.map(d => parseFloat(d.fwi) || 0);

    document.getElementById('info').textContent = `Última actualización: ${fechas[0]} — ${data[0].ubicacion}`;

    crearGrafico("grafTemp","Temperatura (°C)",fechas,temperatura);
    crearGrafico("grafHum","Humedad (%)",fechas,humedad);
    crearGrafico("grafViento","Viento (Km/h)",fechas,viento);
    crearGrafico("grafPresion","Presión (hPa)",fechas,presion);
    crearGrafico("grafIncendio","Riesgo de incendio",fechas,incendio);

  } catch(err) {
    console.error(err);
    document.getElementById('info').textContent = "No se pudieron cargar los datos";
  }
}

function crearGrafico(id,titulo,etiquetas,valores){
  const ctx=document.getElementById(id).getContext('2d');
  new Chart(ctx,{
    type:'line',
    data:{ labels: etiquetas.reverse(), datasets:[{ label:titulo, data:valores.reverse(), borderColor:'rgba(255,255,255,0.8)', backgroundColor:'rgba(255,255,255,0.1)', fill:true, tension:0.3 }]},
    options:{ plugins:{ legend:{ labels:{ color:'#fff' }}}, scales:{ x:{ ticks:{ color:'#ddd' }}, y:{ ticks:{ color:'#ddd' }, beginAtZero:true }}}
  });
}

cargarDatos();
setInterval(cargarDatos,60000);
</script>
</body>
</html>
