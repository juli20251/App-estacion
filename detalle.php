<?php
$chipid = $_GET['chipid'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Detalle estación <?= htmlspecialchars($chipid) ?></title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(135deg, #3c1053, #ad5389);
      color: white;
      margin: 0;
      padding: 0;
      text-align: center;
    }

    header {
      background: rgba(0,0,0,0.3);
      padding: 20px;
      font-size: 22px;
      font-weight: bold;
      letter-spacing: 1px;
      border-bottom: 2px solid rgba(255,255,255,0.3);
    }

    .info {
      margin-top: 15px;
      font-size: 16px;
      color: #f1f1f1;
    }

    .contenedor {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
      gap: 20px;
      padding: 40px;
    }

    .card {
      background: rgba(255,255,255,0.1);
      border-radius: 16px;
      padding: 20px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.3);
      backdrop-filter: blur(5px);
      transition: 0.3s;
    }

    .card:hover {
      transform: translateY(-5px);
      background: rgba(255,255,255,0.15);
    }

    h3 {
      margin-bottom: 10px;
      color: #fff;
    }

    canvas {
      max-width: 100%;
      height: 280px;
    }

    footer {
      background: rgba(0,0,0,0.3);
      padding: 10px;
      font-size: 14px;
      color: #ccc;
      margin-top: 30px;
    }

    button {
      background: #ff4081;
      border: none;
      color: white;
      padding: 10px 20px;
      border-radius: 8px;
      cursor: pointer;
      margin-top: 10px;
      font-size: 15px;
      transition: 0.3s;
    }

    button:hover {
      background: #ff609d;
    }
  </style>
</head>
<body>

  <header>🌦 Detalle de estación <?= htmlspecialchars($chipid) ?></header>

  <div class="info" id="info">Cargando datos reales...</div>

  <div class="contenedor">
    <div class="card"><h3> Temperatura (°C)</h3><canvas id="grafTemp"></canvas></div>
    <div class="card"><h3> Humedad (%)</h3><canvas id="grafHum"></canvas></div>
    <div class="card"><h3> Viento (Km/h)</h3><canvas id="grafViento"></canvas></div>
    <div class="card"><h3> Presión (hPa)</h3><canvas id="grafPresion"></canvas></div>
    <div class="card"><h3> Riesgo de Incendio</h3><canvas id="grafIncendio"></canvas></div>
  </div>

  <button onclick="window.location.href='http://mattprofe.com.ar:81/alumno/10014/app-estacion/?url=panel'">← Volver al panel</button>


  <footer>Actualización automática cada 60 s | Proyecto App-Estación</footer>

  <script>
  const chipid = "<?= $chipid ?>";

  async function cargarDatos() {
    try {
      const resp = await fetch(`datos_estacion.php?chipid=${chipid}&cant=7`);
      const data = await resp.json();

      if (!Array.isArray(data)) {
        console.error("Respuesta inesperada:", data);
        document.getElementById('info').textContent = "Error al obtener datos de la estación.";
        return;
      }

     const fechas = data.map(d => d.fecha_hora || d.fecha || 'N/A');
     const temperatura = data.map(d => parseFloat(d.temperatura) || 0);
     const humedad = data.map(d => parseFloat(d.humedad) || 0);
     const viento = data.map(d => parseFloat(d.viento) || 0);
     const presion = data.map(d => parseFloat(d.presion) || 0);
     const incendio = data.map(d => parseFloat(d.fwi) || 0);

      document.getElementById('info').textContent =
    `Última actualización: ${fechas[0]} —  ${data[0].ubicacion} (${data[0].estacion})`;


      crearGrafico("grafTemp", "Temperatura (°C)", fechas, temperatura);
      crearGrafico("grafHum", "Humedad (%)", fechas, humedad);
      crearGrafico("grafViento", "Viento (Km/h)", fechas, viento);
      crearGrafico("grafPresion", "Presión (hPa)", fechas, presion);
      crearGrafico("grafIncendio", "Riesgo de incendio", fechas, incendio);

    } catch (err) {
      console.error("Error al cargar los datos:", err);
      document.getElementById('info').textContent = "No se pudieron cargar los datos.";
    }
  }

  function crearGrafico(id, titulo, etiquetas, valores) {
    const ctx = document.getElementById(id).getContext('2d');
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: etiquetas.reverse(),
        datasets: [{
          label: titulo,
          data: valores.reverse(),
          borderWidth: 2,
          borderColor: 'rgba(255,255,255,0.8)',
          backgroundColor: 'rgba(255,255,255,0.1)',
          fill: true,
          tension: 0.3,
          pointRadius: 3
        }]
      },
      options: {
        plugins: {
          legend: { labels: { color: '#fff' } }
        },
        scales: {
          x: { ticks: { color: '#ddd' } },
          y: { ticks: { color: '#ddd' }, beginAtZero: true }
        }
      }
    });
  }

  cargarDatos();
  setInterval(cargarDatos, 60000);
  </script>
</body>
</html>
