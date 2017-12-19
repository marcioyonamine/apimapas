<?php 

	// Formatação de datas, valores
	// Retira acentos das strings
	function semAcento($string)
	{
		$newstring = preg_replace("/[^a-zA-Z0-9_.]/", "", strtr($string, "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ ", "aaaaeeiooouucAAAAEEIOOOUUC_"));
		return $newstring;
	}
	//retorna data d/m/y de mysql/date(a-m-d)
	function exibirDataBr($data)
	{
		$timestamp = strtotime($data); 
		return date('d/m/Y', $timestamp);	
	}
	// retorna datatime sem hora
	function retornaDataSemHora($data)
	{
		$semhora = substr($data, 0, 10);
		return $semhora;
	}	
	//retorna data d/m/y de mysql/datetime(a-m-d H:i:s)	
	function exibirDataHoraBr($data)
	{
		$timestamp = strtotime($data); 
		return date('d/m/y - H:i:s', $timestamp);	
	}
	//retorna hora H:i de um datetime
	function exibirHora($data)
	{
		$timestamp = strtotime($data); 
		return date('H:i', $timestamp);	
	}
	//retorna data mysql/date (a-m-d) de data/br (d/m/a)
	function exibirDataMysql($data)
	{ 
		list ($dia, $mes, $ano) = explode ('/', $data);
		if(strlen($dia) == 1){
			$dia = "0".$dia;
		}
		if(strlen($mes) == 1){
			$dia = "0".$mes;
		}

		
		$data_mysql = $ano.'-'.$mes.'-'.$dia;
		return $data_mysql;
	}
	//retorna o endereço da página atual
	function urlAtual()
	{
		$dominio= $_SERVER['HTTP_HOST'];
		$url = "http://" . $dominio. $_SERVER['REQUEST_URI'];
		return $url;
	}
	//retorna valor xxx,xx para xxx.xx
	function dinheiroDeBr($valor)
	{
		$valor = str_ireplace(".","",$valor);
		$valor = str_ireplace(",",".",$valor);
		return $valor;
	}
	//retorna valor xxx.xx para xxx,xx
	function dinheiroParaBr($valor)
	{ 
		$valor = number_format($valor, 2, ',', '.');
		return $valor;
	}
	//use em problemas de codificacao utf-8
	function _utf8_decode($string)
	{
		$tmp = $string;
		$count = 0;
		while (mb_detect_encoding($tmp)=="UTF-8")
		{
			$tmp = utf8_decode($tmp);
			$count++;
		}
		for ($i = 0; $i < $count-1 ; $i++)
		{
			$string = utf8_decode($string);
		}
		return $string;
	}
	//retorna o dia da semana segundo um date(a-m-d)
	function diasemana($data)
	{
		$ano =  substr("$data", 0, 4);
		$mes =  substr("$data", 5, -3);
		$dia =  substr("$data", 8, 9);
		$diasemana = date("w", mktime(0,0,0,$mes,$dia,$ano) );
		switch($diasemana)
		{
			case"0": $diasemana = "Domingo";       break;
			case"1": $diasemana = "Segunda-Feira"; break;
			case"2": $diasemana = "Terça-Feira";   break;
			case"3": $diasemana = "Quarta-Feira";  break;
			case"4": $diasemana = "Quinta-Feira";  break;
			case"5": $diasemana = "Sexta-Feira";   break;
			case"6": $diasemana = "Sábado";        break;
		}
		return "$diasemana";
	}
	//soma(+) ou substrai(-) dias de um date(a-m-d)
	function somarDatas($data,$dias)
	{
		$data_final = date('Y-m-d', strtotime("$dias days",strtotime($data)));	
		return $data_final;
	}


function converterObjParaArray($data) { //função que transforma objeto vindo do json em array

    if (is_object($data)) {
        $data = get_object_vars($data);
    }

    if (is_array($data)) {
        return array_map(__FUNCTION__, $data);
    }
    else {
        return $data;
    }
}

?>