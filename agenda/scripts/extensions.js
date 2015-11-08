/*
	Funções de extensão e funções utilitárias
*/


Date.prototype.isInInterval = function (first, last) {
	return this > first && this < last;
}

Date.prototype.lastMonday = function () {

	var today = new Date();
	var todayOnWeek = today.getDay();
	var result;

	switch (todayOnWeek) {
		case 0:
			result = addDays(today, -6);
			break;
		case 1:
			result = today;
			break;
		default:
			result = addDays(today, todayOnWeek - 1);
			break;
	}

	return result;
}

var monthToString = function(month) {
	switch (month){
		case "1":
			result = "janeiro";
			break;
		case "2":
			result = "fevereiro";
			break;
		case "3":
			result = "março";
			break;
		case "4":
			result = "abril";
			break;
		case "5":
			result = "maio";
			break;
		case "6":
			result = "junho";
			break;
		case "7":
			result = "julho";
			break;
		case "8":
			result = "agosto";
			break;
		case "9":
			result = "setembro";
			break;
		case "10":
			result = "outubro";
			break;
		case "11":
			result = "novembro";
			break;
		case "12":
			result = "dezembro";
			break;
		default:
			result = "Erro";
			break;
	}
	return result;
}

var dayWeektoStringBr = function (dayWeek) {

	switch (dayWeek){
		
		case 0:
			result = "segunda-feira";
		break;
		case 1:
			result = "terça-feira";
		break;
		case 2:
			result = "quarta-feira";
		break;
		case 3:
			result = "quinta-feira";
		break;
		case 4:
			result = "sexta-feira";
		break;
		case 5:
			result = "sábado";
		break;
		case 6:
			result = "domingo";
		break;
	}
	return result;
}

var stringToDate = function (strDate) {
	//split transforma string delimitada pelo parâmetro em array, inverso de join.
	var year = strDate.split('-')[0];
	var day = strDate.split('-')[2];
	var month = parseInt(strDate.split('-')[1]) - 1;//Zero Based
	return new Date(year, month, day);
}

var addDays = function (date, days) {
	var result = new Date(date);
	result.setDate(date.getDate() + days);
	return result;
}

var dateToString = function (date) {
	var datestr = "";
	datestr += date.getFullYear().toString();
	datestr += "-";
	datestr += paddingLeft(2, 0, (date.getMonth() + 1).toString());
	datestr += "-";
	datestr += paddingLeft(2, 0, date.getDate().toString());
	return datestr;
}

var paddingLeft = function (padding, char, value) {
	//Adiciona char a esquerda, padding vezes, em value
	var pad = "";
	for (var i = 0; i < padding; i++) {
		pad += char;
	}
	return pad.substring(0, pad.length - value.length) + value;
}

var differenceDateInDays = function (first, last) {
	var timeDiff = Math.abs(last.getTime() - first.getTime());
	return Math.ceil(timeDiff / (1000 * 3600 * 24));
}

var stringDateToBrStringDate = function (date) {
	var ano = date.split('-')[0];
	var mes = date.split('-')[1];
	var dia = date.split('-')[2];	

	var date = new Date(date);
	var dayWeek = date.getDay();
	
	return dayWeektoStringBr(dayWeek) + ", " + dia + " de " + monthToString(mes) + " de " + ano;
}
