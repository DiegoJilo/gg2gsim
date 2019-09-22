<?php

set_time_limit(20);

class YouTube {

	public $cid;


	public function lastVideo ($cid){

	$ulast = 'https://www.googleapis.com/youtube/v3/search?part=snippet&channelId=' .$cid. '&maxResults=1&order=date&type=video&key=xxxxxxxxxxxxxxx';
	$glast = @file_get_contents($ulast);
	$dlast = json_decode($glast, true);

	$vid = @$dlast['items'][0]['id']['videoId'];
	$vname = @$dlast['items'][0]['snippet']['title'];

	//Condition

		$ret = "Assista meu último vídeo: " .$vname. "\n http://youtu.be/" .$vid;

		return $ret;
	}

}

class METARandTAF{

	public $icao;


	public function icaoMetar ($icao){

			$url = 'https://aviationweather.gov/adds/dataserver_current/httpparam?dataSource=metars&requestType=retrieve&format=xml&hoursBeforeNow=4&mostRecent=true&stationString='.$icao;
			$get = $this->JSON($url);
			$dec = @json_decode($get, true);
			$res = @$dec['data']['METAR']['raw_text'];


			if(isset($res)){

				$metar = "*METAR para " .strtoupper($icao). ":*\n" .$res;

			}elseif($this->icaoTAF($icao) == true && empty($res)){

				$metar = "*METAR para " .strtoupper($icao). ":*\n Infelizmente não temos o METAR para ".strtoupper($icao);

			}elseif($this->icaoTAF($icao) === false and empty($res)) {

				$metar = "Informe um ICAO válido para obter a consulta.\n(Ex: /metar SBSP)";

			}

			return $metar;
		}

		public function icaoTAF ($icao){

			$url = 'https://aviationweather.gov/adds/dataserver_current/httpparam?dataSource=tafs&requestType=retrieve&format=xml&hoursBeforeNow=4&mostRecent=true&stationString='.$icao;
			$get = $this->JSON($url);
			$dec = @json_decode($get, true);
			$res = @$dec['data']['TAF']['raw_text'];


			if(isset($res)){

				$taf = "*TAF para " .strtoupper($icao). ":*\n" .$res;
				return $taf;

			}elseif($this->icaoMetar($icao) == true && empty($res)){

				$taf = "*TAF para " .strtoupper($icao). ":*\nInfelizmente não temos o TAF para ".strtoupper($icao);

				return $taf;

			}else{

				return false;
			}


	}


		public function decMetar($icao){
			$url = 'https://aviationweather.gov/adds/dataserver_current/httpparam?dataSource=metars&requestType=retrieve&format=xml&hoursBeforeNow=4&mostRecent=true&stationString='.$icao;
			$get = $this->JSON($url);
			$dec = @json_decode($get, true);
			$raw  = $dec['data']['METAR']['raw_text'];
			$temp = $dec['data']['METAR']['temp_c'];
			$dir = $dec['data']['METAR']['wind_dir_degrees'];
			$vel = $dec['data']['METAR']['wind_speed_kt'];

			//Para a Visibilidade
			$pos = strpos ($raw, 'KT');
			$pos2 = strpos($raw, ' ', $pos+3);
			$len = $pos2 - $pos;
			$vis = substr($raw, ($pos+3), $len-3 );

			return $vis;
		}

	 	public function JSON ($web) {
	        $fileContents= file_get_contents($web);
	        $fileContents = str_replace(array("\n", "\r", "\t"), '', $fileContents);
	        $fileContents = trim(str_replace('"', "'", $fileContents));
	        $simpleXml = simplexml_load_string($fileContents);
	        $json = json_encode($simpleXml);

       		return $json;
    }

}


?>
