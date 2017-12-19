window.Agenda = {};

Agenda.DataHoje = "";

Agenda.Cache = {};
Agenda.Cache.LocationIds = "";
Agenda.Cache.Eventos = {};
Agenda.Cache.Eventos.Hoje = [];
Agenda.Cache.Eventos.Semana = [];
Agenda.Cache.Eventos.Mes = [];

Agenda.API = {};
Agenda.API.BaseUrl = "http://spcultura.prefeitura.sp.gov.br/api/";
Agenda.API.IDCCSP = "425";
Agenda.API.LocationIds = "205";