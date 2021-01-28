<?php

namespace App\Http\Controllers;

use Exception;
use Google_Service_Oauth2;
use Google_Service_Sheets;
use Illuminate\Http\Request;
use Google_Client;

class SheetsController extends Controller
{
    public function show()
    {
        session_start();

        // Fill CLIENT ID, CLIENT SECRET ID, REDIRECT URI from Google Developer Console
        $client_id = '479725925945-2srgk49iiegafglnvlmo1qj9a2ucuir7.apps.googleusercontent.com';
        $client_secret = 'GKGJINC31buPPjqqhmlRdA3I';
        $redirect_uri = 'http://a6df19675aac.ngrok.io/api_auth';
        $simple_api_key = 'AIzaSyAbYnZY75CF8BP9O_J9-_KO8lp9VbdncK4';

        //Create Client Request to access Google API
        $client = new Google_Client();
        $client->setApplicationName("PHP Google OAuth Login Example");
        $client->setClientId($client_id);
        $client->setClientSecret($client_secret);
        $client->setRedirectUri($redirect_uri);
        $client->setDeveloperKey($simple_api_key);
        $client->addScope("https://www.googleapis.com/auth/userinfo.email");

        //Send Client Request
        $objOAuthService = new Google_Service_Oauth2($client);
//Logout
        if (isset($_REQUEST['logout'])) {
            unset($_SESSION['access_token']);
            $client->revokeToken();
            header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL)); //redirect user back to page
        }

//Authenticate code from Google OAuth Flow
//Add Access Token to Session
        if (isset($_GET['code'])) {
            $client->authenticate($_GET['code']);
            $_SESSION['access_token'] = $client->getAccessToken();
            header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
        }

//Set Access Token to make Request
        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
            $client->setAccessToken($_SESSION['access_token']);
        }

//Get User Data from Google Plus
//If New, Insert to Database
        $userData = null;
        $authUrl = null;
        if ($client->getAccessToken()) {
            $userData = $objOAuthService->userinfo->get();
            if(!empty($userData)) {
                !d($userData);
//                $objDBController = new DBController();
//                $existing_member = $objDBController->getUserByOAuthId($userData->id);
//                if(empty($existing_member)) {
//                    $objDBController->insertOAuthUser($userData);
//                }
            }
            !d($client->getAccessToken());
            $_SESSION['access_token'] = $client->getAccessToken();
        } else {
            $authUrl = $client->createAuthUrl();
        }

        return view('oauth', [
            'authUrl' => $authUrl,
            'userData' => $userData
        ]);
    }

    /**
     * Returns an authorized API client.
     * @return Google_Client the authorized client object
     */
    function getClient()
    {
        $client = new Google_Client();
        $client->setApplicationName('Google Sheets API PHP Quickstart');
        $client->setScopes(Google_Service_Sheets::SPREADSHEETS_READONLY);
        $client->setAuthConfig(storage_path('app/credentials/') . 'credentials.json');
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        // Load previously authorized token from a file, if it exists.
        // The file token.json stores the user's access and refresh tokens, and is
        // created automatically when the authorization flow completes for the first
        // time.
        $tokenPath = 'token.json';
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true, 512, JSON_THROW_ON_ERROR);
            $client->setAccessToken($accessToken);
        }

        // If there is no previous token or it's expired.
        if ($client->isAccessTokenExpired()) {
            // Refresh the token if possible, else fetch a new one.
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                // Request authorization from the user.
                $authUrl = $client->createAuthUrl();
                printf("Open the following link in your browser:\n%s\n", $authUrl);
                print 'Enter verification code: ';
//                $authCode = trim(fgets(STDIN));
//
//                // Exchange authorization code for an access token.
//                $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
//                $client->setAccessToken($accessToken);
//
//                // Check to see if there was an error.
//                if (array_key_exists('error', $accessToken)) {
//                    throw new Exception(join(', ', $accessToken));
//                }
            }
            // Save the token to a file.
            if (!file_exists(dirname($tokenPath))) {
                if (!mkdir($concurrentDirectory = dirname($tokenPath), 0700, true) && !is_dir($concurrentDirectory)) {
                    throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
                }
            }
            file_put_contents($tokenPath, json_encode($client->getAccessToken(), JSON_THROW_ON_ERROR));
        }
        return $client;
    }

    protected function saveThis()
    {
        // Get the API client and construct the service object.
        $client = $this->getClient();
        $service = new Google_Service_Sheets($client);

        // Prints the names and majors of students in a sample spreadsheet:
        // https://docs.google.com/spreadsheets/d/1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OgvE2upms/edit
        $spreadsheetId = '1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OgvE2upms';
        $range = 'Class Data!A2:E';
        $response = $service->spreadsheets_values->get($spreadsheetId, $range);
        $values = $response->getValues();

        if (empty($values)) {
            print "No data found.\n";
        } else {
            print "Name, Major:\n";
            foreach ($values as $row) {
                // Print columns A and E, which correspond to indices 0 and 4.
                printf("%s, %s\n", $row[0], $row[4]);
            }
        }

    }
}
