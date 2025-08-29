<?php
require 'autoload.php';
use Google\Cloud\Dialogflow\V2\SessionsClient;
use Google\Cloud\Dialogflow\V2\QueryInput;
use Google\Cloud\Dialogflow\V2\TextInput;

putenv('GOOGLE_APPLICATION_CREDENTIALS=vendor/lifetours-t9dm-ab9011e87c19.json');
$projectId = 'lifetours-t9dm';

$input = json_decode(file_get_contents('php://input'), true);
$text = $input['message'] ?? 'Hai';
$sessionId = $input['session_id'] ?? uniqid();

try {
    $sessionsClient = new SessionsClient();
    $session = $sessionsClient->sessionName($projectId, $sessionId);

    $textInput = new TextInput();
    $textInput->setText($text);
    $textInput->setLanguageCode('id-ID');

    $queryInput = new QueryInput();
    $queryInput->setText($textInput);

    $response = $sessionsClient->detectIntent($session, $queryInput);
    $queryResult = $response->getQueryResult();
    $reply = $queryResult->getFulfillmentText() ?: 'Maaf, saya tidak mengerti.';

    echo json_encode([
        'success' => true,
        'reply' => $reply,
        'session_id' => $sessionId
    ]);

    $sessionsClient->close();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
}
?>