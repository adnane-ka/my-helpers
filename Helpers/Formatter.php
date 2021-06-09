<?php 
namespace Adnane\ResponseFormatter;

use Illuminate\Http\Request;
trait Formatter
{
    /** 
    * Final shapper
    * @param $data 
    * @return json 
    * */
    private function returned($data ,$headers = [] ,$callback = null)
    {
        $commonHeaders = $this->commonHeaders ?? [];

        if(isset($this->responseType))
        {
            switch ($this->responseType) {
                case 'json':

                    return response()
                    
                    ->json($data)
                    
                    ->headers(array_merge($headers ,$commonHeaders))
                    
                    ->withCallback($callback);

                    break;
            }
        }
        return response()->json($data);
    }

    /** 
    * Successful Requests Handler 
    * @param $data 
    * @return json 
    * */
    public function formatSuccess($details = [])
    {
        return $this->returned(
            [
                "api" => [
                    'version' => $this->apiVersion ?? '1.0',
                ],
                "meta" => [
                    'description' => $details['description'] ?? 'successful request.',
                    'status' => $details['status'] ?? true,
                    'code' => $details['code'] ?? 200,
                    'resource' => $details['resource'] ?? null,
                    'items' => $details['items'] ?? 0,
                    'status_text' => $details['status_text'] ?? 'OK',
                ],
                "links" => [
                    'previous' => $details['previous'] ?? null,
                    'next' => $details['next'] ?? null,
                ],
                "result" => $details['data'],    
            ],
            $details['headers'] ?? null ,
            $details['callback'] ?? null
        );
    }
    
    /** 
    * Failed Requests Handler
    * @param $data 
    * @return json 
    * */
    public function formatError($details = [])
    {
        return $this->returned(
            [
                "api" => [
                    'version' => $this->apiVersion ?? '1.0',
                ],
                "meta" => [
                    'description' => $details['description'] ?? 'failed request.',
                    'status' => $details['status'] ?? false,
                    'code' => $details['code'] ?? 422,
                    'resource' => $details['resource'] ?? null,
                    'items' => $details['items'] ?? 0,
                    'status_text' => $details['status_text'] ?? 'ERROR',
                ],
                "errors" => $details['error'],    
            ]
        );
    }
}
