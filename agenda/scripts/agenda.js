$(document).ready(function () {

	init();

});

var printEventosFromCache = function (eventosCache, seletorContainer) {

	$(seletorContainer).children().remove();

	for (var key in eventosCache) {
		var eventos = eventosCache[key];
		$(seletorContainer).append('<li class="date-label">' + stringDateToBrStringDate(key) + '</li>');
		if (eventos.length > 0) {

			$.each(eventos, function () {
				//Para cada evento (this) em eventos, invoca a função que cria o template.
				$(seletorContainer).append('<li>' + templateEvento(this) + '</li>');
			});
		} else {
			$(seletorContainer).append('<li>Não há eventos até o momento...</li>');
		}
	}

	$('.lista-eventos li div.event-details').hide();
}

var templateEvento = function (evento) {
	//Obtém o template do HTML e substitui os valores em evento
	for (var i = 0; i <= 10; i++) {
		evento.Descricao = evento.Descricao.replace('\n', '<br/>');
	};
	var template = $('#templateevento').html();
	template = template.replace('{{NOME}}', evento.Nome);
	template = template.replace('{{HORARIO}}', evento.Horario);
	template = template.replace('{{HORAINICIO}}', evento.HoraInicio);
	template = template.replace('{{DESCRICAO}}', evento.Descricao);
	template = template.replace('{{LOCAL}}', evento.Local);
	template = template.replace('{{PRECO}}', evento.Preco);
	template = template.replace('{{DURACAO}}', evento.Duracao);
	template = template.replace('{{CLASSIFICACAO}}', evento.Classificacao);
	template = template.replace('{{LINGUAGEM}}', evento.Linguagem);
	//retorna o evento de acordo com o template
	return template;
}

var getEvents = function (spaceIdsString, inicio) {

	//Cria o array de eventos vazio
	var eventos = [];
	//Processa o mês completo
	intervalo = 30;
	//transforma a string inicio em objeto data
	var dataInicial = stringToDate(inicio);
	//adiciona intervalo dias em dataInicial 
	var dataFim = addDays(dataInicial, intervalo);
	//URL padrão da API
	var url = Agenda.API.BaseUrl + "event/findByLocation";
	//Se os espaços vierem nulos, adiciona espaços do CCSP
	if (!spaceIdsString) {
		spaceIdsString = Agenda.API.LocationIds;
	}
	//Chamada para a API
	$.getJSON(url,
		{	//Parâmetros da consulta
			'space': 'IN(' + spaceIdsString + ')',
			'@select': 'name,id,occurrences,terms,longDescription,classificacaoEtaria',
			'@from': dateToString(dataInicial),
			'@to': dateToString(dataFim),
			'@order': 'name ASC'
		},
		function (data) {
			tratarEventos(data, dataInicial);
			$('#loader').hide();
			printEventosFromCache(Agenda.Cache.Eventos.Hoje, '#dia ul.lista-eventos');
			printEventosFromCache(Agenda.Cache.Eventos.Semana, '#semana ul.lista-eventos');
			printEventosFromCache(Agenda.Cache.Eventos.Mes, '#mes ul.lista-eventos');
		});
}

var getSpaceIds = function (ccspId, shouldAddCCSP, dataInicial) {
	//Seta o ID do CCSP caso venha nulo
	if (!ccspId) {
		ccspId = Agenda.API.IDCCSP;
	}
	//URL padrão da API para obter os Spaces baseado no Agent
	var url = Agenda.API.BaseUrl + "space/getChildrenIds/" + ccspId;

	//Verifica se já temos os IDs das locações, se não tivermos, solicita na API
	if (Agenda.Cache.LocationIds == "") {

		$.getJSON(url, function (data) {
			//Concatena os spaces que vieram da API separados por vírgula
			var spaceIdsString = data.join(',');

			//Se true, adiciona o ID do CCSP aos Spaces
			if (shouldAddCCSP == true) {
				spaceIdsString = spaceIdsString + ',' + ccspId;
			}

			//Guarda os IDs das locações no cache
			Agenda.Cache.LocationIds = spaceIdsString;

			//Invoca a função que solicitará os eventos na API
			getEvents(spaceIdsString, dataInicial);
		});

	} else {
		//Se os IDs de locação estiverem no cache usa-se o valor em cache, sem chamada na API
		getEvents(Agenda.Cache.LocationIds, dataInicial);
	}
}

var tratarEventos = function (eventos, dataInicial) {
	
	Agenda.Cache.Eventos.Mes = [];
	var data = dataInicial;
	var diasNoIntervalo = [];

	for(var i=0; i<30; i++){
		Agenda.Cache.Eventos.Mes[dateToString(data)] = [];
		if (i < 7) { Agenda.Cache.Eventos.Semana[dateToString(data)] = [];	}
		data = addDays(data, 1);
	}

	todosEventos = [];
	
	$.each(eventos, function () {

		var evento = this;

		$.each(evento.occurrences, function () {

			var inicio = stringToDate(this.rule.startsOn);
			var fim = stringToDate(this.rule.until);
			var frequency = this.rule.frequency;
			var days = [];

		   /*********************************************************************************************
			*	Eventos de frequência semanal															*
			*********************************************************************************************/
		
			if (frequency == "weekly") {

				//Determina os dias da semana em nos quais haverá evento
				for (var i = 0; i < 6; i++) {
					days[i] = this.rule.day[i] == "on" ? true : false;
				}

				var data = inicio;
				var intervalo = differenceDateInDays(inicio, fim);

				for (var i = 0; i <= intervalo ; i++) {

					if (days[data.getDay()]) {

						var oEvento = {
							'Nome': evento.name,
							'Horario': this.rule.description,
							'HoraInicio': this.rule.startsAt,
							'Data': dateToString(data),
							'Local': this.space.name,
							'Descricao': evento.longDescription,
							'Duracao': this.rule.duration,
							'Preco': this.rule.price,
							'Linguagem': evento.terms.linguagem.join(", "),
							'Classificacao': evento.classificacaoEtaria
						};
						todosEventos.push(oEvento);
					}
					data = addDays(data, 1);
				}
			}

		   /*********************************************************************************************
			*	Eventos de frequência diária 															*
			*********************************************************************************************/

			if (frequency == "daily") {
				var data = inicio;
				var intervalo = differenceDateInDays(inicio, fim);

				for (var i = 0; i <= intervalo ; i++) {

					var oEvento = {
						'Nome': evento.name,
						'Horario': this.rule.description,
						'HoraInicio': this.rule.startsAt,
						'Data': dateToString(data),
						'Local': this.space.name,
						'Descricao': evento.longDescription,
						'Duracao': this.rule.duration,
						'Preco': this.rule.price,
						'Linguagem': evento.terms.linguagem.join(", "),
						'Classificacao': evento.classificacaoEtaria
					};

					console.log(evento.name + " " + dateToString(data));
					todosEventos.push(oEvento);
					data = addDays(data, 1);
				}
			}

		   /*********************************************************************************************
			*	Eventos de frequência única 															*
			*********************************************************************************************/

			if (frequency == "once") {

				var oEvento = {
					'Nome': evento.name,
					'Horario': this.rule.description,
					'HoraInicio': this.rule.startsAt,
					'Data': dateToString(inicio),
					'Local': this.space.name,
					'Descricao': evento.longDescription,
					'Duracao': this.rule.duration,
					'Preco': this.rule.price,
					'Linguagem': evento.terms.linguagem.join(", "),
					'Classificacao': evento.classificacaoEtaria
				};

				todosEventos.push(oEvento);
			}

		});

	});

	for (var key in Agenda.Cache.Eventos.Mes) {

		$.each(todosEventos, function () {
			if (this.Data == key) {
				Agenda.Cache.Eventos.Mes[key].push(this);
			}
		});

		Agenda.Cache.Eventos.Mes[key].sort(
			function (eventoA, eventoB) {
				horaA = parseInt(eventoA.HoraInicio.replace(':', ''));
				horaB = parseInt(eventoB.HoraInicio.replace(':', ''));
				return horaA - horaB;
			}
		);

	}

	Agenda.Cache.Eventos.Hoje[dateToString(dataInicial)] = Agenda.Cache.Eventos.Mes[dateToString(dataInicial)];

	for (var key in Agenda.Cache.Eventos.Semana) {
		Agenda.Cache.Eventos.Semana[key] = Agenda.Cache.Eventos.Mes[key];
	}

}

var init = function () {

	Agenda.DataHoje = $('#dataatual').val();

	getSpaceIds(Agenda.API.IDCCSP, true, Agenda.DataHoje);

	$('ul.lista-eventos').on('click', 'li', function () {

		$(this).find('div.event-details').slideToggle();

	});

}