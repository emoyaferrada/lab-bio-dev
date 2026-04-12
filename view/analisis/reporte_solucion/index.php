<?php
// Simulate Database Data
$depth1_data = array(
    array('date' => '07-11-2024', 'lab' => 'SS-2840', 'ph' => 6.1, 'ce' => 0.39, 'n_no3' => 1.5, 'n_nh4' => 2.8, 'n' => 4.3, 'p' => 2.5, 'k' => 14.3, 'ca' => 22.6, 'mg' => 5.8, 's' => 21.1, 'cl' => 42.5, 'na' => 8.2, 'fe' => 5.5, 'mn' => '<0.1', 'zn' => '<0.1', 'cu' => '<0.1', 'b' => 0.6),
    array('date' => '10-12-2024', 'lab' => 'SS-3105', 'ph' => 6.5, 'ce' => 0.55, 'n_no3' => 5.8, 'n_nh4' => 4.0, 'n' => 9.8, 'p' => 2.7, 'k' => 20.7, 'ca' => 28.8, 'mg' => 7.5, 's' => 20.2, 'cl' => 56.7, 'na' => 9.1, 'fe' => 0.6, 'mn' => '<0.1', 'zn' => '<0.1', 'cu' => '<0.1', 'b' => 0.3),
    array('date' => '15-01-2025', 'lab' => 'SS-144', 'ph' => 6.3, 'ce' => 0.58, 'n_no3' => 7.3, 'n_nh4' => 11.4, 'n' => 18.7, 'p' => 15.2, 'k' => 33.3, 'ca' => 19.2, 'mg' => 6.2, 's' => 34.5, 'cl' => 56.7, 'na' => 12.2, 'fe' => 32.0, 'mn' => 0.4, 'zn' => 0.2, 'cu' => '<0.1', 'b' => 2.0),
);

$depth2_data = array(
    array('date' => '07-11-2024', 'lab' => 'SS-2841', 'ph' => 5.9, 'ce' => 0.32, 'n_no3' => 1.7, 'n_nh4' => 4.2, 'n' => 5.9, 'p' => 1.1, 'k' => 11.3, 'ca' => 14.1, 'mg' => 4.3, 's' => 18.5, 'cl' => 28.4, 'na' => 9.9, 'fe' => 9.2, 'mn' => '<0.1', 'zn' => '<0.1', 'cu' => '<0.1', 'b' => 0.8),
    array('date' => '10-12-2024', 'lab' => 'SS-3106', 'ph' => 6.1, 'ce' => 0.32, 'n_no3' => 4.3, 'n_nh4' => 7.2, 'n' => 11.5, 'p' => 1.8, 'k' => 8.6, 'ca' => 14.2, 'mg' => 3.8, 's' => 12.3, 'cl' => 28.4, 'na' => 9.7, 'fe' => 5.4, 'mn' => '<0.1', 'zn' => '<0.1', 'cu' => '<0.1', 'b' => 0.4),
    array('date' => '15-01-2025', 'lab' => 'SS-145', 'ph' => 6.5, 'ce' => 0.55, 'n_no3' => 3.8, 'n_nh4' => 25.2, 'n' => 29.0, 'p' => 6.6, 'k' => 19.2, 'ca' => 8.4, 'mg' => 3.7, 's' => 34.6, 'cl' => 56.7, 'na' => 9.3, 'fe' => 34.3, 'mn' => 0.3, 'zn' => 0.2, 'cu' => 0.2, 'b' => 2.0),
);

function renderTable($data) {
    echo '<table>';
    echo '<thead>
            <tr>
                <th class="date-col">Fecha<br>muestreo</th>
                <th class="lab-col">N° Lab</th>
                <th>pH</th>
                <th>CE sol<br>(dS/m)</th>
                <th>N-N03<br>(mg/L)</th>
                <th>N-NH4<br>(mg/L)</th>
                <th>N (mg/L)</th>
                <th>P<br>(mg/L)</th>
                <th>K<br>(mg/L)</th>
                <th>Ca<br>(mg/L)</th>
                <th>Mg<br>(mg/L)</th>
                <th>S-S04<br>(mg/L)</th>
                <th>Cl<br>(mg/L)</th>
                <th>Na<br>(mg/L)</th>
                <th>Fe<br>(mg/L)</th>
                <th>Mn<br>(mg/L)</th>
                <th>Zn (mg/L)</th>
                <th>Cu<br>(mg/L)</th>
                <th>B<br>(mg/L)</th>
            </tr>
          </thead>';
    echo '<tbody>';
    foreach ($data as $row) {
        echo '<tr>';
        foreach ($row as $cell) {
            echo "<td>{$cell}</td>";
        }
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Lab-Bio</title>
    <link rel="stylesheet" href="style.css">
    <!-- Scripts for PDF and Charts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>seleccionado <?php echo "SELECCIONADOS: ".$_GET["sel"];?></h1>
    
    <div class="controls">
        <button id="download-pdf" class="btn">Descargar PDF</button>
    </div>

    <div id="report-container">
        <!-- Header Title -->
        <div class="header-title">
            <h1>LABORATORIO QUÍMICO AGRÍCOLA LAB-BIO</h1>
            <p>Km 3.5 Camino Antuco, Santa Emilia Lote B1 - Los Ángeles / fono: 43-2542308</p>
        </div>

        <!-- Info Section -->
        <div class="info-section">
            <div class="info-col">
                <div class="info-row"><span class="label">Razón Social/Nombre:</span> <span class="value">Soc. Agrícola y Ganadera El Diamelo Ltda</span></div>
                <div class="info-row"><span class="label">RUT:</span> <span class="value">76439910-2</span></div>
                <div class="info-row"><span class="label">Predio:</span> <span class="value">Diamelo</span></div>
                <div class="info-row"><span class="label">Cuartel/Potrero:</span> <span class="value">Cuartel 1</span></div>
            </div>
            <div class="info-col">
                <div class="info-row"><span class="label">Tipo de análisis:</span> <span class="value">Solución suelo</span></div>
                <div class="info-row"><span class="label">Especie:</span> <span class="value">Avellano Europeo</span></div>
                <div class="info-row"><span class="label">Responsable muestra:</span> <span class="value">Bastián Cuevas</span></div>
            </div>
            <div class="info-col logo-col">
                <div class="logo-placeholder">
                    <img src="logo_reporte.png">
                </div>
                <div style="margin-top: 20px;">Pagina: 1</div>
            </div>
        </div>


        <!-- Analisis de suelo -->
        <div class="report-title">
            REPORTE DE MONITOREO DE ANÁLISIS QUÍMICO SOLUCIÓN DE SUELO
        </div>

        <!-- Table 1 -->
        <div class="depth-section">
            <div class="depth-title">Profundidad: 0 - 30 cm</div>
            <?php renderTable($depth1_data); ?>
        </div>

        <!-- Table 2 -->
        <div class="depth-section">
            <div class="depth-title">Profundidad: 30 - 60 cm</div>
            <?php renderTable($depth2_data); ?>
        </div>

        <!-- Charts -->
        <div class="charts-section">
            <div class="depth-title" style="border:none; margin-bottom: 10px;">Gráficos de seguimiento:</div>
            
            <div class="charts-legend">
                <div class="legend-item"><div class="legend-color" style="background:#ed7d31"></div> Valor máximo</div>
                <div class="legend-item"><div class="legend-color" style="background:#a5a5a5"></div> Valor mínimo</div>
                <div class="legend-item"><div class="legend-color" style="background:#4472c4"></div> Resultado análisis</div>
            </div>

            <div class="charts-grid">
                <div class="chart-wrapper"><canvas id="chart-n"></canvas></div>
                <div class="chart-wrapper"><canvas id="chart-p"></canvas></div>
                <div class="chart-wrapper"><canvas id="chart-k"></canvas></div>
                <div class="chart-wrapper"><canvas id="chart-ca"></canvas></div>
            </div>
        </div>
    

        <!-- Analisis Foliar  -->
        <div class="report-title">
            REPORTE DE MONITOREO DE ANÁLISIS QUÍMICO FOLIAR
        </div>

        <!-- Table 1 -->
        <div class="depth-section">
            <div class="depth-title"></div>
            <?php renderTable($depth1_data); ?>
        </div>


        <!-- Charts -->
        <div class="charts-section">
            <div class="depth-title" style="border:none; margin-bottom: 10px;">Gráficos de seguimiento:</div>
            
            <div class="charts-legend">
                <div class="legend-item"><div class="legend-color" style="background:#ed7d31"></div> Valor máximo</div>
                <div class="legend-item"><div class="legend-color" style="background:#a5a5a5"></div> Valor mínimo</div>
                <div class="legend-item"><div class="legend-color" style="background:#4472c4"></div> Resultado análisis</div>
            </div>

            <div class="charts-grid">
                <div class="chart-wrapper"><canvas id="chart-n"></canvas></div>
                <div class="chart-wrapper"><canvas id="chart-p"></canvas></div>
                <div class="chart-wrapper"><canvas id="chart-k"></canvas></div>
                <div class="chart-wrapper"><canvas id="chart-ca"></canvas></div>
            </div>
        </div>

        <!-- SOLUCIÓN SALIDA DE EMISOR -->
        <div class="report-title">
            SOLUCIÓN SALIDA DE EMISOR
        </div>

        <!-- Table 1 -->
        <div class="depth-section">
            <div class="depth-title"></div>
            <?php renderTable($depth1_data); ?>
        </div>



        <!-- REPORTE DE MONITOREO DE ANÁLISIS QUÍMICO AGUA DE RIEGO -->
        <div class="report-title">
            REPORTE DE MONITOREO DE ANÁLISIS QUÍMICO AGUA DE RIEGO
        </div>

        <!-- Table 1 -->
        <div class="depth-section">
            <div class="depth-title"></div>
            <?php renderTable($depth1_data); ?>
        </div>

    </div>

    <script src="script.js"></script>
</body>
</html>
