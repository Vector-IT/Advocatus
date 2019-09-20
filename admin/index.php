<?php 
	session_start();

	ini_set("log_errors", 1);
	ini_set("error_log", "php-error.log");

	require_once 'php/datos.php';

	if (!isset($_SESSION['is_logged_in'])){
		header("Location:login.php");
		die();
	}

	header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
	header("Pragma: no-cache"); // HTTP 1.0.
	header("Expires: 0"); // Proxies.	
?>
<!DOCTYPE html>
<html>
<head>
	<?php require_once 'php/linksHeader.php';?>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.min.js"></script>
</head>
<body>
	<?php
		$config->crearMenu();
		
		require_once 'php/header.php';
	?>
	
	<div class="container-fluid">	
		<div class="page-header">
			<h2>Consola de Administraci&oacute;n</h2>
		</div>
		
		<p class="lead">
			Bienvenido a la consola de administraci&oacute;n de <?php echo $config->titulo ?><br>
			Utilice el men&uacute; situado en el margen izquierdo de la pantalla para acceder a las distintas 
			secciones del sistema.			
		</p>
		<hr>

		<div class="row">
			<div class="col-md-6">
				<canvas id="chart1" width="100%" height="50"></canvas>
			</div>
			<div class="col-md-6">
				<canvas id="chart2" width="100%" height="50"></canvas>
			</div>
		</div>
	</div>	
	
	<?php
		require_once 'php/footer.php';
	?>

<?php
	//Gráfico de los úlitmos 30 días
	$strSQL = "SELECT DATE_FORMAT(FechCarr, '%d/%m/%Y') FechCarr, SUM(ImpoTota) ImpoTota";
	$strSQL.= $crlf."FROM carritos";
	$strSQL.= $crlf."WHERE NumeEstaCarr >= 7";
	$strSQL.= $crlf."AND FechCarr >= DATE_ADD(SYSDATE(), INTERVAL -31 DAY)";
	$strSQL.= $crlf."GROUP BY DATE_FORMAT(FechCarr, '%d/%m/%Y')";
	
	$imp30dias = $config->cargarTabla($strSQL);

	$labels1 = '';
	$data1 = '';
	
	$fecha = new DateTime();
	$fecha->modify('-30 day');

	$hoy = new DateTime();
	$hoy->modify('+1 day');

	while ($hoy->format('Y-m-d') != $fecha->format('Y-m-d')) {
		if ($labels1 != '') {
			$labels1.= ', ';
			$data1.= ', ';
		}
		$blnDato = false;
		$labels1.= "'{$fecha->format('d-m-Y')}'";
		
		if ($imp30dias->num_rows > 0) {
			while ($fila = $imp30dias->fetch_assoc()) {
				if ($fila["FechCarr"] == $fecha->format('d/m/Y')) {
					$blnDato = true;
					$data1.= "'{$fila["ImpoTota"]}'";
				}
			}
			$imp30dias->data_seek(0);
		}

		if (!$blnDato) {
			$data1.= "'0'";
		}

		$fecha->modify('+1 day');
	}

	//Gráfico prductos mas vendidos
	$strSQL = "SELECT p.NombProd, SUM(cd.CantProd) CantProd";
	$strSQL.= $crlf."FROM carritosdetalles cd";
	$strSQL.= $crlf."INNER JOIN productos p ON cd.NumeProd = p.NumeProd";
	$strSQL.= $crlf."WHERE cd.NumeCarr IN (SELECT NumeCarr FROM carritos c WHERE c.NumeEstaCarr >= 7)";
	$strSQL.= $crlf."GROUP BY p.NombProd";
	$strSQL.= $crlf."ORDER BY 2 DESC";
	$strSQL.= $crlf."LIMIT 10";

	$prodVend = $config->cargarTabla($strSQL);

	$labels2 = '';
	$data2 = '';
	$colors2 = '';
	$I = 0;

	if ($prodVend->num_rows > 0) {
		while ($fila = $prodVend->fetch_assoc()) {
			if ($labels2 != '') {
				$labels2.= ', ';
				$data2.= ', ';
				$colors2.= ', ';
			}

			$labels2.= "'{$fila["NombProd"]}'";
			$data2.= "'{$fila["CantProd"]}'";
			$colors2.= "chartColors1[{$I}]";
			
			($I < 6)? $I++ : $I = 0;
		}
		$prodVend->data_seek(0);
	}

?>
	<script>
		var chartColors = {
			red: 'rgb(255, 99, 132)',
			orange: 'rgb(255, 159, 64)',
			yellow: 'rgb(255, 205, 86)',
			green: 'rgb(75, 192, 192)',
			blue: 'rgb(54, 162, 235)',
			purple: 'rgb(153, 102, 255)',
			grey: 'rgb(201, 203, 207)'
		};

		var chartColors1 = [
			'rgb(255, 99, 132)',
			'rgb(255, 159, 64)',
			'rgb(255, 205, 86)',
			'rgb(75, 192, 192)',
			'rgb(54, 162, 235)',
			'rgb(153, 102, 255)',
			'rgb(201, 203, 207)'
		];

		var ctx1 = document.getElementById("chart1").getContext('2d');
		var myChart1 = new Chart(ctx1, {
			type: 'line',
            data: {
                labels: [<?php echo $labels1?>],
                datasets: [{
                    backgroundColor: chartColors.red,
                    borderColor: 'rgb(255, 99, 132)',
                    data: [<?php echo $data1?>],
                    fill: false,
                }]
            },
            options: {
                responsive: true,
                title:{
                    display:true,
                    text:'Ventas en los últimos 30 días',
					fontSize: 16
				},
				legend: {
					display:false
				},
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: false,
                            labelString: 'Fecha'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Importes'
                        }
                    }]
                }
            }
		});	

		var ctx2 = document.getElementById("chart2").getContext('2d');
		var myChart2 = new Chart(ctx2, {
			type: 'pie',
            data: {
				datasets: [{
					data: [<?php echo $data2?>],
					backgroundColor: [<?php echo $colors2?>],
					label: 'Dataset 1'
				}],
				labels: [<?php echo $labels2?>]
			},
			options: {
				responsive: true,
				legend: {
					position: 'top',
				},
				title: {
					display: true,
					text: 'Top 10 Productos más vendidos',
					fontSize: 16
				},
				animation: {
					animateScale: true,
					animateRotate: true
				}
			}
		});	
	</script>
</body>
</html>