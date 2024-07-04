<?php
include_once ("../utils/base_url.php");

require_once BASE_PATH . 'vendor/autoload.php';

session_start();

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

$client = new Google_Client();
$client->setClientId($_ENV['CLIENT_ID']);
$client->setClientSecret($_ENV['CLIENT_SECRET']);
$client->setRedirectUri($_ENV['REDIRECT_URI']);
$client->addScope(Google_Service_Drive::DRIVE);
$client->addScope(Google_Service_Docs::DOCUMENTS);
$client->setAccessType('offline');

if (!isset($_GET['code']) && !isset($_SESSION['google_access_token'])) {
    $auth_url = $client->createAuthUrl();
    header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
    exit;
} elseif (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $_SESSION['google_access_token'] = $token;
    header('Location: ' . filter_var(BASE_URL . 'backend/create_document.php', FILTER_SANITIZE_URL));
    exit;
}

$client->setAccessToken($_SESSION['google_access_token']);

$docsService = new Google_Service_Docs($client);
$driveService = new Google_Service_Drive($client);

$document = new Google_Service_Docs_Document(
    array(
        'title' => 'Resposta - FMJ Forms'
    )
);
$document = $docsService->documents->create($document);

$fileId = $document->getDocumentId();

$file = new Google_Service_Drive_DriveFile(
    array(
        'name' => 'Resposta - FMJ Forms',
        'mimeType' => 'application/vnd.google-apps.document'
    )
);

$driveService->files->update($fileId, $file);

echo "Documento criado com sucesso. ID: " . $fileId;
