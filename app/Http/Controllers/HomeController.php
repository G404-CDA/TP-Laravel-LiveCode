<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="CDA Laravel API",
 *     description="API de recettes de cuisine",
 *     @OA\Contact(
 *         email="hamza@garage404.com"
 *     ),
 *     @OA\License(
 *         name="Licence API",
 *         url="https://www.monapi.com/licence"
 *     )
 * )
 */
class HomeController extends BaseController
{


   /**
     * @OA\Get(
     *     path="/api/index",
     *     summary="Index",
     *     @OA\Response(response=200, description="Index"),
     * )
     */
    public function index()
    {
        return [
            'name' => 'Laravel 8',
            'version' => '8.0.0',
            'author' => 'Taylor Otwell',
        ];
    }

}
