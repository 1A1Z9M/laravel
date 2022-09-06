<?php

namespace App\Http\Controllers;

use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

class MedicineController extends Controller
{
    public function index() {
        return view('index');
    }

    public function extract(Request $request) {
        $client = new ImageAnnotatorClient();
        $image = $client->createImageObject(file_get_contents($request->image));
        $response = $client->textDetection($image);
        if(!is_null($response->getError())) {
            return ['result' => false];
        }
        $annotations = $response->getTextAnnotations();
        $description = str_replace('"""', '', $annotations[0]->getDescription());
        return [
            'result' => true,
            'text' => $description
        ];
    }
}
