<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RakutenRws_Client;


class SearchController extends Controller
{
    //
    // public function search() {
    //     return view('search');
    // }

    public function getRakutenItems(){
        $client = new RakutenRws_Client();
        // アプリIDセット
        $client->setApplicationId('1092944396312171301');
        $response = $client->execute('IchibaItemSearch', array(
          'keyword' => ''
        ));
        if (! $response->isOk()) {
            return'Error:'.$response->getMessage();
        } else {
            $items = array();
            foreach ($response as $key => $rekutenItem) {
                $items[$key]['title'] = $rekutenItem['itemName'];
                $items[$key]['price'] = $rekutenItem['itemPrice'];
                if($rekutenItem['imageFlag']) {
                    $imgSrc = $rekutenItem['mediumImageUrls'][0]['imageUrl'];
                    $items[$key]['img_src'] = preg_replace('/^http:/', 'https:', $imgSrc);
                }
            }
            // return response()->json($response->getData()); 
            return $items;
            }
    }
}