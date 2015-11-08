<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>CCSP - Agenda</title>
	<link rel="stylesheet" href="bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
	
	<div class="calendar-container">
		<?php
			$today = date('Y-m-d');
			echo "<input type='hidden' id='dataatual' value='".$today."'/>";
		?>
		<!--<input type='hidden' id='dataatual' value='2015-10-15' />-->
		<div class="container">
			<h2>Programação</h2>
			<ul class="nav nav-tabs">
				<li class="active"><a data-toggle="tab" href="#dia">Dia</a></li>
				<li><a data-toggle="tab" href="#semana">Semana</a></li>
				<li><a data-toggle="tab" href="#mes">Mês</a></li>
			</ul>

			<div class="tab-content">
				<div id="dia" class="tab-pane fade in active" data-intervalo="0">
					<h3>HOJE<img id="loader" src="images/loader.gif"/></h3>
					<ul class="lista-eventos"></ul>
				</div>
				<div id="semana" class="tab-pane fade" data-intervalo="7">
					<ul class="lista-eventos"></ul>
				</div>
				<div id="mes" class="tab-pane fade" data-intervalo="30">
					<ul class="lista-eventos"></ul>
				</div>
			</div>

			<div id="templateevento" style="display:none;">
				<div class="title-wrapper">
					<span class="event-time">{{HORAINICIO}}</span>
					<span class="event-reply-status">&nbsp;</span>
					<span class="event-title">{{LINGUAGEM}} - {{NOME}}</span>
				</div>
				<div class="event-details">
					<p>Onde: {{LOCAL}}</p>
					<p>{{DESCRICAO}}</p>
				</div>
			</div>			
		</div>
	</div>
	<script src="http://code.jquery.com/jquery-2.1.4.min.js" type="text/javascript"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="bootstrap/js/bootstrap.min.js"></script>
	<script src="scripts/globals.js" type="text/javascript"></script>
	<script src="scripts/extensions.js" type="text/javascript"></script>
	<script src="scripts/agenda.js" type="text/javascript"></script>
</body>
</html>