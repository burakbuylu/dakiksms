<?php

/**
 * Class DakikSMS
 * 
 * @author Burak Buylu
 *         @web http://www.burakbuylu.com
 *         @mail burak@burtinet.com
 *         @date 23 Şubat 2018
 */

namespace dakikSMS;

class dakikSMS
{
	private $username;
	private $password;
	private $title;
	private $receivers = [];
	private $message;

	private $error;
	
	private $url = 'http://www.dakiksms.com/api/tr/xml_api_ileri.php';


	public function __construct($username, $password, $title)
    {
		$this->username = $username;
		$this->password = $password;
		$this->title = $title;
    }
	
	public function message($msg)
	{
		$this->message = $msg;
	}
	
	public function receivers($receivers)
	{
		$this->receivers = $receivers;
	}
	
	public function send()
	{
		if(count($this->receivers) < 1) return false;
		$receivers = '';
		foreach($this->receivers AS $rcv):
			$receivers . = "<alicilar>$rcv</alicilar>".PHP_EOL;
		endforeach;
		$xml = '<SMS>'.
			'<oturum>'.
				'<kullanici>'.$this->username.'</kullanici>'.
				'<sifre>'.$this->password.'</sifre>'.
			'</oturum>'.
			'<baslik>'.$this->title.'</baslik>'.
			'<mesaj>'.
				'<metin>'.$this->message.'</metin>'.
				$receivers.
			'</mesaj>'.
			'<karaliste></karaliste>'.
			'<tarih></tarih>'.
			'<izin_link>true</izin_link>'.
			'<izin_telefon>true</izin_telefon>'.
		'</SMS>';
		
		$result	= $this->sendRequest($xml);
		
		if($result)
		{
			if (substr($result, 0, 2) == 'OK')
			{
				list($ok, $smsId) = explode('|', $result);
				return $smsId;
			}
			elseif (substr($result, 0, 3) == 'ERR')
			{
				list($err, $msg) = explode('|', $sonuc);
				$this->error = 'Hata (' . $err . ') oluştu. ' . $msg;
				return false;
			}
			else
			{
				$this->error = 'Bir hata oluştu: ' . $result;
				return false;
			}
		}else{
			return false;
		}
	}
	
	public function getError(){
		return $this->error;
	}
	
	public function sendRequest($send_xml,$header_type=array('Content-Type: text/xml'))
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$this->url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$send_xml);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_HTTPHEADER,$header_type);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 120);

		$result = curl_exec($ch);

		return $result;
	}
}