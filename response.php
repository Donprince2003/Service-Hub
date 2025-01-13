<?php
require "vendor/autoload.php";

use GeminiAPI\Client;
use GeminiAPI\Resources\Parts\TextPart;

$data=json_decode(file_get_contents("php://input"));
$text="If asked about a worker profession, respond in a friendly and approachable tone by selecting an appropriate job title from the following list: 'Electrician', 'Plumber', 'Carpenter', 'Welder', 'Mechanic', 'Construction Worker', 'Truck Driver', 'Painter', 'Mason', 'HVAC Technician', 'Landscaper', 'Roofer', 'Glazier', 'Pest Control Worker', 'Sheet Metal Worker', 'Insulation Worker', 'Maintenance Worker', 'Pipefitter', 'Steelworker', 'Assembler'. Make sure explain the response is easy to read and understand.if a sutable profetion is not found the replay must be clear that the worker in not avilable in this site and give recommendation a profetion that may accept the job from the table if any. If the question is not related to a worker profession, reply with 'Please ask a relevent question'. The question is: ".$data->text;
$client=new Client("your key");
$response=$client->geminiPro()->generateContent(
    new TextPart($text),
);

echo $response->text();
