<?php


//Funções
include_once('funcao.php');
$yt = new YouTube();
$met = new METARandTAF();



//----------------------------------------------------------//

$botToken = "926215742:AAHx4rIeO2IW5lWr1w8DgFvDcEau6e_eAPI";

$url = "https://api.telegram.org/bot".$botToken;

$updates = file_get_contents('php://input');
$decode = json_decode($updates, true);

$chatid = $decode["message"]["chat"]["id"];
$nome = @$decode["message"]["chat"]["first_name"];
$mensagem = $decode["message"]["text"];
$tipo = $decode["message"]["chat"]['type'];

//Canal do Galeno
$canal = 'UCvI5-W5HGB7bcEj8oESsEiA';


//--------------------------------------------------------//


switch($mensagem){

	case "/start":
		sendMessage($chatid, "Olá ".$nome." seja bem vindo ao Bot.");
		break;

	case "/lastvid":
		sendMessage($chatid, ($yt->lastVideo($canal)));
		break;

	case "/ajuda":
		sendMessage($chatid, "/lastvid - Mostra o último vídeo do Galeno\n/config - Mostra as configurações do PC\n/metar ICAO - Diz o METAR e o TAF do aeroporto");
		break;

	case "/config":
		sendParse($chatid, "*MEU SETUP / MY SPECS:*\n---------------------------\n- Placa Mãe ASUS Z97Plus-BR\n- Processador Quarta Geração Intel i7 4770k\n- Placa de Vídeo NVIDIA GeForce GTX 960 (PNY) 2Gb\n- Memória Corsair Vengeance Pro 12Gb DDR3\n- Fonte Cooler Master 650W\n- Water Cooler Cooler Master 120XL\n- Gabinete Cooler Master\n- Joystick Saitek X52 Pro\n- VIVO Fibra Internet 200/100Mbps (down/up)\n- Simulador Prepar3D 3.x\n- Headphone AKG Pro\n- Microfone shotgun ST31Low Noise\n- Mesa som Behringer Xenyx Q502 USB\n- Monitor LG UltraWide\n- Monitor Sansung\n- Som Bose 2 canais");
		break;

	case strpos($mensagem, '/metar') === 0:

			/*if($mensagem == "/metar 2gsim"){

				sendParse($chatid, "*Metar para 2GSIM*\n2GSIM 280200Z 00000KT CAVOK 22/18 Q1013 NOSIG - SEMPRE CAVOK Comandantes!");
			}*/
			if(strlen($mensagem)>6 && strlen($mensagem)<=11){

				$airport = substr($mensagem, 7);
				sendParse($chatid, $met->icaoMetar($airport)."\n \n".$met->icaoTAF($airport)."\n\n by @GG2GSim");
			}

		break;

	case strpos($mensagem, '/metardec') === 0:

		if(strlen($mensagem)>9){

				$airport = substr($mensagem, 10);
				sendParse($chatid, $met->decMetar($airport));
			}


		break;

	default:

		if($tipo == "group" || $tipo == "supergroup"){

			@sendMessage($chatid, "");

		}else{

			sendMessage($chatid, "Por favor insira um comando válido. Digite /ajuda para ver os comandos.");
	}
}



function sendMessage ($chatid, $message) {

        $result = $GLOBALS[url]."/sendMessage?chat_id=".$chatid."&text=".urlencode(utf8_encode($message));
        @file_get_contents($result);

}

function sendParse ($chatid, $message) {

        $result = $GLOBALS[url]."/sendMessage?chat_id=".$chatid."&parse_mode=Markdown&text=".urlencode(utf8_encode($message));
        @file_get_contents($result);
 }
?>
