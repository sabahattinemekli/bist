<?php

	error_reporting(E_ALL);
	ini_set('display_errors', '1');

	$keepsession = isset($_GET['keepsession']);
	

	$GLOBALS['cookiefile'] = $cookiefile;
	$hisseapi = "http://bigpara.hurriyet.com.tr/api/v1/hisse/list";
	$hisseyuzeysel = "http://bigpara.hurriyet.com.tr/api/v1/borsa/hisseyuzeysel/";

	function websorgu($url){
		
		$user_agent = "Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36";
		$cookiefile = dirname(__FILE__).'/cookie/deneme.txt';
	
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Requested-With: XMLHttpRequest","Accept-Encoding: deflate"));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 15);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiefile);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiefile);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

		return curl_exec($ch);
	}

	echo "<br><br>";
	echo '<font color="#FF0000">///////SİSTEM BAŞLADI</font>';
	echo "<br><br>";

	//session sayfasi
	
	echo "<br>///////HİSSE LİSTELEME SAYFASI/////////<br>";
	
	
	
	//print_r($result);
	
	$i = 1;
	$result = websorgu($hisseapi);
	$hisseler = json_decode($result);
	foreach($hisseler->data as $hissebilgi){
		//echo $i.": ";
		$hissekodu = $hissebilgi->kod;
		//echo "<br>";
		$i++;
		
		
		
		$hissedetay = websorgu($hisseyuzeysel.$hissekodu);
		$hissedetay = json_decode($hissedetay);
		if($hissedetay==NULL){
			continue;
		}
		
		
		//print_r($hissedetay);
		//echo "<br>";
		
		
		if(!ISSET($hissedetay->data->hisseYuzeysel->haftayuksek)){
			
			//echo "<br>";
			//echo $hissekodu." verileri eksik geldi";
			continue;
		}
		
		$haftayuksek = $hissedetay->data->hisseYuzeysel->haftayuksek;
		$haftadusuk = $hissedetay->data->hisseYuzeysel->haftadusuk;
		$alis = $hissedetay->data->hisseYuzeysel->alis;
		
		if($alis == 0){
			continue;
		}
		
		//echo "<br><br>";
		
		//echo ($haftayuksek - $haftadusuk);
		//echo "<br>";
		//echo ($haftayuksek / $haftadusuk);
		//echo "<br>";
		//echo ($haftayuksek / $alis);
		
		if($haftayuksek / $alis > 1.10){
			echo "<br>";
			echo $hissekodu." haftalık en yükseğe göre %10 düşmüş...";
			echo "<br>";
			echo $haftayuksek." Haftayuksek<br>";
			echo $haftadusuk." Haftadusuk<br>";
			echo $alis." Alış<br>";
		}
		
		
		
		
		
		
	}
	
	


echo "<br><br>";
echo '<font color="#FF0000">///////HER ŞEY BİTTİ</font>';
echo "<br><br>";

	
?>
