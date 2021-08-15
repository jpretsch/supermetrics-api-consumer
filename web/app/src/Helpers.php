<?
namespace App\Helpers;

class Helpers
{
    public static function outputErrorMessage($message, $code=404)
    {
        header('Content-Type: application/json', true, $code);
        echo json_encode([$code, $message], JSON_PRETTY_PRINT);
        die();
    }

    public static function outputJson($data, $code=200)
    {
        ksort($data); //order by keys (weeks, months and such)
        header('Content-Type: application/json', true, $code);
        echo json_encode([$code, $data], JSON_PRETTY_PRINT);
        die();
    }
}