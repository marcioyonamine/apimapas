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
Agenda.API.IDCCSP = "169";
Agenda.API.LocationIds = "554,170,230,544,534,541,172,542,540,1400,171,545,546,543,169";