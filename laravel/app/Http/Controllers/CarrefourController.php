<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\carrefour_despensa_producto;
use App\Models\carrefour_frescos_producto;
use App\Models\carrefour_bebidas_producto;
use App\Models\carrefour_perfumeria_e_higiene_producto;
use App\Models\carrefour_limpieza_y_hogar_producto;
use App\Models\carrefour_bebe_producto;
use App\Models\carrefour_mascotas_producto;
use App\Models\carrefour_parafarmacia_producto;
use Illuminate\Support\Facades\DB;

class CarrefourController extends Controller
{
    public function CarrefourIndex () {
        $despensa_products = DB::table('carrefour_despensa_productos')->limit(3)->get();
        $frescos_products = DB::table('carrefour_frescos_productos')->limit(3)->get();
        $bebidas_products = DB::table('carrefour_bebidas_productos')->limit(3)->get();
        $perfumeria_e_higiene_products = DB::table('carrefour_perfumeria_e_higiene_productos')->limit(3)->get();
        $limpieza_y_hogar_products = DB::table('carrefour_limpieza_y_hogar_productos')->limit(3)->get();
        $bebe_products = DB::table('carrefour_bebe_productos')->limit(3)->get();
        $mascotas_products = DB::table('carrefour_mascotas_productos')->limit(3)->get();
        $parafarmacia_products = DB::table('carrefour_parafarmacia_productos')->limit(3)->get();

        $products = $despensa_products->concat($frescos_products)->concat($bebidas_products)->concat($perfumeria_e_higiene_products)->concat($limpieza_y_hogar_products)->concat($bebe_products)->concat($mascotas_products)->concat($parafarmacia_products);

        return view('carrefour.index',compact('products'));
    }


    public function CarrefourDespensa(){
        $products = DB::table('carrefour_despensa_productos')->simplePaginate(24);
        return view('carrefour.despensa', compact('products'));
    }

    public function CarrefourFrescos(){
        $products = DB::table('carrefour_frescos_productos')->simplePaginate(24);
        return view('carrefour.frescos', compact('products'));
    }

    public function CarrefourBebidas(){
        $products = DB::table('carrefour_bebidas_productos')->simplePaginate(24);
        return view('carrefour.bebidas', compact('products'));
    }

    public function CarrefourPerfumeria(){
        $products = DB::table('carrefour_perfumeria_e_higiene_productos')->simplePaginate(24);
        return view('carrefour.perfumeria_e_higiene', compact('products'));
    }

    public function CarrefourLimpieza(){
        $products = DB::table('carrefour_limpieza_y_hogar_productos')->simplePaginate(24);
        return view('carrefour.limpieza_y_hogar', compact('products'));
    }

    public function CarrefourBebe(){
        $products = DB::table('carrefour_bebe_productos')->simplePaginate(24);
        return view('carrefour.bebe', compact('products'));
    }

    public function CarrefourMascotas(){
        $products = DB::table('carrefour_mascotas_productos')->simplePaginate(24);
        return view('carrefour.mascotas', compact('products'));
    }

    public function CarrefourParafarmacia(){
        $products = DB::table('carrefour_parafarmacia_productos')->simplePaginate(24);
        return view('carrefour.parafarmacia', compact('products'));
    }

    public function CarrefourLoadProducts(){

        ini_set('max_execution_time', 240);

        cargar_carrefour_despensa();
        cargar_carrefour_frescos();
        cargar_carrefour_bebidas();
        cargar_carrefour_perfumeria_e_higiene();
        cargar_carrefour_limpieza_y_hogar();
        cargar_carrefour_bebe();
        cargar_carrefour_mascotas();
        cargar_carrefour_parafarmacia();

        return redirect()->route('carrefour.index');
    }

    /*Función para el buscador de la aplicación, que recibe un texto que introduce el usuario en el input del formulario,
     y busca dicho texto en los campos de la tabla productos de la base de datos*/
     public function search(Request $request){
        $search=$request->input('search');
        $despensa_products=DB::table('carrefour_despensa_productos')->where('name','LIKE','%'.$search.'%')->get();
        $frescos_products=DB::table('carrefour_frescos_productos')->where('name','LIKE','%'.$search.'%')->get();
        $bebidas_products=DB::table('carrefour_bebidas_productos')->where('name','LIKE','%'.$search.'%')->get();
        $perfumeria_e_higiene_products=DB::table('carrefour_perfumeria_e_higiene_productos')->where('name','LIKE','%'.$search.'%')->get();
        $limpieza_y_hogar_products=DB::table('carrefour_limpieza_y_hogar_productos')->where('name','LIKE','%'.$search.'%')->get();
        $bebe_products=DB::table('carrefour_bebe_productos')->where('name','LIKE','%'.$search.'%')->get();
        $mascotas_products=DB::table('carrefour_mascotas_productos')->where('name','LIKE','%'.$search.'%')->get();
        $parafarmacia_products=DB::table('carrefour_parafarmacia_productos')->where('name','LIKE','%'.$search.'%')->get();

        $products = $despensa_products->concat($frescos_products)->concat($bebidas_products)->concat($perfumeria_e_higiene_products)
            ->concat($limpieza_y_hogar_products)->concat($bebe_products)->concat($mascotas_products)->concat($parafarmacia_products);
        return view('carrefour.index', compact('products'));
    }


}

function cargar_carrefour_despensa (){

    //Borramos los productos anteriores que pueda haber en la tabla
    $products = carrefour_despensa_producto::all();
    foreach ($products as $product) $product->delete();

    $offset = 0;
    $fin = false;
    while (!$fin) {
        $target_url = 'https://www.carrefour.es/supermercado/la-despensa/cat20001/c?offset='.$offset;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $target_url);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $html = curl_exec($ch);

        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        libxml_clear_errors();
        $dom->loadHTML($html);
        $xpath = new \DOMXpath($dom);


        $scripts = $xpath->query("//script");
        $filtro = substr($scripts[5]->textContent, 25, -122);
        $json = json_decode($filtro);
        $items = $json->productCardList->results->items;
        foreach ($items as $item) {
            $newProduct = new carrefour_despensa_producto();
            $newProduct->name = utf8_decode($item->name);
            $newProduct->product_id = $item->product_id;
            $newProduct->price = substr(trim($item->price), 0, -7);
            $newProduct->image=utf8_decode($item->images->desktop);
            $newProduct->save();
        }

        $offset += 24;

        $paginador = $xpath->query("//div[contains(@class,'pagination')]/span[contains(@class,'pagination__next--disabled')]");
        if (count($paginador) > 0) {
            $fin = true;
        }

        curl_close($ch);
    }
}
function cargar_carrefour_frescos (){

    //Borramos los productos anteriores que pueda haber en la tabla
    $products = carrefour_frescos_producto::all();
    foreach ($products as $product) $product->delete();

    $offset = 0;
    $fin = false;
    while (!$fin) {
        $target_url = 'https://www.carrefour.es/supermercado/productos-frescos/cat20002/c?offset='.$offset;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $target_url);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $html = curl_exec($ch);

        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        libxml_clear_errors();
        $dom->loadHTML($html);
        $xpath = new \DOMXpath($dom);


        $scripts = $xpath->query("//script");
        $filtro = substr($scripts[5]->textContent, 25, -122);
        $json = json_decode($filtro);
        $items = $json->productCardList->results->items;
        foreach ($items as $item) {
            $newProduct = new carrefour_frescos_producto();
            $newProduct->name = utf8_decode($item->name);
            $newProduct->product_id = $item->product_id;
            $newProduct->price = substr(trim($item->price), 0, -7);
            $newProduct->image=utf8_decode($item->images->desktop);
            $newProduct->save();
        }

        $offset += 24;

        $paginador = $xpath->query("//div[contains(@class,'pagination')]/span[contains(@class,'pagination__next--disabled')]");
        if (count($paginador) > 0) {
            $fin = true;
        }

        curl_close($ch);
    }
}
function cargar_carrefour_bebidas (){

    //Borramos los productos anteriores que pueda haber en la tabla
    $products = carrefour_bebidas_producto::all();
    foreach ($products as $product) $product->delete();

    $offset = 0;
    $fin = false;
    while (!$fin) {
        $target_url = 'https://www.carrefour.es/supermercado/bebidas/cat20003/c?offset='.$offset;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $target_url);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $html = curl_exec($ch);

        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        libxml_clear_errors();
        $dom->loadHTML($html);
        $xpath = new \DOMXpath($dom);


        $scripts = $xpath->query("//script");
        $filtro = substr($scripts[5]->textContent, 25, -122);
        $json = json_decode($filtro);
        $items = $json->productCardList->results->items;
        foreach ($items as $item) {
            $newProduct = new carrefour_bebidas_producto();
            $newProduct->name = utf8_decode($item->name);
            $newProduct->product_id = $item->product_id;
            $newProduct->price = substr(trim($item->price), 0, -7);
            $newProduct->image=utf8_decode($item->images->desktop);
            $newProduct->save();
        }

        $offset += 24;

        $paginador = $xpath->query("//div[contains(@class,'pagination')]/span[contains(@class,'pagination__next--disabled')]");
        if (count($paginador) > 0) {
            $fin = true;
        }

        curl_close($ch);
    }
}
function cargar_carrefour_perfumeria_e_higiene (){

    //Borramos los productos anteriores que pueda haber en la tabla
    $products = carrefour_perfumeria_e_higiene_producto::all();
    foreach ($products as $product) $product->delete();

    $offset = 0;
    $fin = false;
    while (!$fin) {
        $target_url = 'https://www.carrefour.es/supermercado/perfumeria-e-higiene/cat20004/c?offset='.$offset;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $target_url);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $html = curl_exec($ch);

        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        libxml_clear_errors();
        $dom->loadHTML($html);
        $xpath = new \DOMXpath($dom);


        $scripts = $xpath->query("//script");
        $filtro = substr($scripts[5]->textContent, 25, -122);
        $json = json_decode($filtro);
        $items = $json->productCardList->results->items;
        foreach ($items as $item) {
            $newProduct = new carrefour_perfumeria_e_higiene_producto();
            $newProduct->name = utf8_decode($item->name);
            $newProduct->product_id = $item->product_id;
            $newProduct->price = substr(trim($item->price), 0, -7);
            $newProduct->image=utf8_decode($item->images->desktop);
            $newProduct->save();
        }

        $offset += 24;

        $paginador = $xpath->query("//div[contains(@class,'pagination')]/span[contains(@class,'pagination__next--disabled')]");
        if (count($paginador) > 0) {
            $fin = true;
        }

        curl_close($ch);
    }
}
function cargar_carrefour_limpieza_y_hogar (){

    //Borramos los productos anteriores que pueda haber en la tabla
    $products = carrefour_limpieza_y_hogar_producto::all();
    foreach ($products as $product) $product->delete();

    $offset = 0;
    $fin = false;
    while (!$fin) {
        $target_url = 'https://www.carrefour.es/supermercado/limpieza-y-hogar/cat20005/c?offset='.$offset;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $target_url);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $html = curl_exec($ch);

        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        libxml_clear_errors();
        $dom->loadHTML($html);
        $xpath = new \DOMXpath($dom);


        $scripts = $xpath->query("//script");
        $filtro = substr($scripts[5]->textContent, 25, -122);
        $json = json_decode($filtro);
        $items = $json->productCardList->results->items;
        foreach ($items as $item) {
            $newProduct = new carrefour_limpieza_y_hogar_producto();
            $newProduct->name = utf8_decode($item->name);
            $newProduct->product_id = $item->product_id;
            $newProduct->price = substr(trim($item->price), 0, -7);
            $newProduct->image=utf8_decode($item->images->desktop);
            $newProduct->save();
        }

        $offset += 24;

        $paginador = $xpath->query("//div[contains(@class,'pagination')]/span[contains(@class,'pagination__next--disabled')]");
        if (count($paginador) > 0) {
            $fin = true;
        }

        curl_close($ch);
    }
}
function cargar_carrefour_bebe (){

    //Borramos los productos anteriores que pueda haber en la tabla
    $products = carrefour_bebe_producto::all();
    foreach ($products as $product) $product->delete();

    $offset = 0;
    $fin = false;
    while (!$fin) {
        $target_url = 'https://www.carrefour.es/supermercado/bebe/cat20006/c?offset='.$offset;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $target_url);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $html = curl_exec($ch);

        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        libxml_clear_errors();
        $dom->loadHTML($html);
        $xpath = new \DOMXpath($dom);


        $scripts = $xpath->query("//script");
        $filtro = substr($scripts[5]->textContent, 25, -122);
        $json = json_decode($filtro);
        $items = $json->productCardList->results->items;
        foreach ($items as $item) {
            $newProduct = new carrefour_bebe_producto();
            $newProduct->name = utf8_decode($item->name);
            $newProduct->product_id = $item->product_id;
            $newProduct->price = substr(trim($item->price), 0, -7);
            $newProduct->image=utf8_decode($item->images->desktop);
            $newProduct->save();
        }

        $offset += 24;

        $paginador = $xpath->query("//div[contains(@class,'pagination')]/span[contains(@class,'pagination__next--disabled')]");
        if (count($paginador) > 0) {
            $fin = true;
        }

        curl_close($ch);
    }
}
function cargar_carrefour_mascotas (){

    //Borramos los productos anteriores que pueda haber en la tabla
    $products = carrefour_mascotas_producto::all();
    foreach ($products as $product) $product->delete();

    $offset = 0;
    $fin = false;
    while (!$fin) {
        $target_url = 'https://www.carrefour.es/supermercado/mascotas/cat20007/c?offset='.$offset;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $target_url);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $html = curl_exec($ch);

        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        libxml_clear_errors();
        $dom->loadHTML($html);
        $xpath = new \DOMXpath($dom);


        $scripts = $xpath->query("//script");
        $filtro = substr($scripts[5]->textContent, 25, -122);
        $json = json_decode($filtro);
        $items = $json->productCardList->results->items;
        foreach ($items as $item) {
            $newProduct = new carrefour_mascotas_producto();
            $newProduct->name = utf8_decode($item->name);
            $newProduct->product_id = $item->product_id;
            $newProduct->price = substr(trim($item->price), 0, -7);
            $newProduct->image=utf8_decode($item->images->desktop);
            $newProduct->save();
        }

        $offset += 24;

        $paginador = $xpath->query("//div[contains(@class,'pagination')]/span[contains(@class,'pagination__next--disabled')]");
        if (count($paginador) > 0) {
            $fin = true;
        }

        curl_close($ch);
    }
}
function cargar_carrefour_parafarmacia (){

    //Borramos los productos anteriores que pueda haber en la tabla
    $products = carrefour_parafarmacia_producto::all();
    foreach ($products as $product) $product->delete();

    $offset = 0;
    $fin = false;
    while (!$fin) {
        $target_url = 'https://www.carrefour.es/supermercado/parafarmacia/cat20008/c?offset='.$offset;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $target_url);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $html = curl_exec($ch);

        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        libxml_clear_errors();
        $dom->loadHTML($html);
        $xpath = new \DOMXpath($dom);


        $scripts = $xpath->query("//script");
        $filtro = substr($scripts[5]->textContent, 25, -122);
        $json = json_decode($filtro);
        $items = $json->productCardList->results->items;
        foreach ($items as $item) {
            $newProduct = new carrefour_parafarmacia_producto();
            $newProduct->name = utf8_decode($item->name);
            $newProduct->product_id = $item->product_id;
            $newProduct->price = substr(trim($item->price), 0, -7);
            $newProduct->image=utf8_decode($item->images->desktop);
            $newProduct->save();
        }

        $offset += 24;

        $paginador = $xpath->query("//div[contains(@class,'pagination')]/span[contains(@class,'pagination__next--disabled')]");
        if (count($paginador) > 0) {
            $fin = true;
        }

        curl_close($ch);
    }
}
