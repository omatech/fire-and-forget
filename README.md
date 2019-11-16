# Usage

$url="https://postman-echo.com/get";
$params=['test'=>'testval'];
$faf = new FireAndForget($url, $params);
$faf->send();

# Test
phpunit --bootstrap vendor/autoload.php tests