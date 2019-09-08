<?php


namespace App\Services\Google\Sheets;


use Google\Spreadsheet\DefaultServiceRequest;
use Google\Spreadsheet\ServiceRequestFactory;
use Google\Spreadsheet\SpreadsheetService;
use Illuminate\Support\Facades\Log;
use Matrix\Exception;

class GoogleSheets
{
    public function writeRow(array $row)
    {
        try{
            $client = new \Google_Client();
            //$client->setApplicationName('Google Sheets API PHP Quickstart');
            $client->setScopes(\Google_Service_Sheets::SPREADSHEETS);
            $client->setAuthConfig(storage_path('app/google/key_table.json'));
            $client->setAccessType('offline');
            if ($client->isAccessTokenExpired()) {
                $client->refreshTokenWithAssertion();
            }

            $service = new \Google_Service_Sheets( $client );
            $spreadsheetId = '13-LfX4G1-bUXspx5neYLNhvKi_8FC3GVA8QBcC1MQ6g';
            $response = $service->spreadsheets_values->get($spreadsheetId, 'Sheet');
            $body    = new \Google_Service_Sheets_ValueRange();
            $body->setValues([
                $row
            ]);
            $options = [
                'valueInputOption' => 'USER_ENTERED',
                'insertDataOption' => 'INSERT_ROWS'
            ];
            $service->spreadsheets_values->append($spreadsheetId, 'Sheet!A241', $body, $options);
        }catch (\Exception $e){
            Log::channel('custom')->error($e);
        }
    }
}

//
//$client = new \Google_Client();
////$client->setApplicationName('Google Sheets API PHP Quickstart');
//$client->setScopes(\Google_Service_Sheets::SPREADSHEETS);
//$client->setAuthConfig(storage_path('app/google/key_table.json'));
//$client->setAccessType('offline');
//if ($client->isAccessTokenExpired()) {
//    $client->refreshTokenWithAssertion();
//}
//
//$service = new \Google_Service_Sheets( $client );
//$spreadsheetId = '13-LfX4G1-bUXspx5neYLNhvKi_8FC3GVA8QBcC1MQ6g';
////$response = $service->spreadsheets_values->get($spreadsheetId, 'Sheet');
//$body    = new \Google_Service_Sheets_ValueRange();
//$body->setValues([['John', 'Doe', 'Doe', 'Doe', 'Doe', 'Doe', 'Doe', 'Doe', 'Doe', 'Doe', 'Doe', 'Doe', 'Doe', 'Doe', 'Doe', 'Doe', 'Doe', 'Doe', 'Doe']]);
//
//
//$options = array( 'valueInputOption' => 'USER_ENTERED' );
//$service->spreadsheets_values->append($spreadsheetId, 'Sheet!A239', $body, $options);