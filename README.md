# dakiksms
Dakik SMS için SMS Gönderme Kütüphanesi

Kullanım;

$username = '123456879';
$password = '987654321';
$title    = 'Burak Buylu';

$dakiksms = new dakikSMS($username,$password,$title);

$dakiksms->receivers = [
  '5321234567',
  '5359876543'
];

$dakiksms->message = 'Hello Dakik SMS';

$result = $dakiksms->send();

if(!$result){
  echo $dakiksms->getError();
}

var_dump($result);
