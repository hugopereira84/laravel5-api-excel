<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

//model of recipe
use App\Recipe;

class RecipeController extends Controller
{
    /*****************************************/
    //http://www.sitepoint.com/build-rest-resources-laravel//

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        try{
            $statusCode = 200;
            $response = [
                'photos'  => []
            ];

            /*$photos = Photo::all()->take(9);

            foreach($photos as $photo){

                $response['photos'][] = [
                    'id' => $photo->id,
                    'user_id' => $photo->user_id,
                    'url' => $photo->url,
                    'title' => $photo->title,
                    'description' => $photo->description,
                    'category' => $photo->category,
                ];
            }*/

        }catch (Exception $e){
            $statusCode = 400;
        }finally{
            return Response::json($response, $statusCode);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        echo '222';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $photo = Recipe::find($id);
        exit();
        /*try{
            $photo = Recipe::find($id);
            echo '11';
            exit();
            $statusCode = 200;
            $response = [ "photo" => [
                'id' => (int) $id,
                'user_id' => (int) $photo->user_id,
                'title' => $photo->title,
                'url' => $photo->url,
                'category' => (int) $photo->category,
                'description' => $photo->description
            ]];

        }catch(Exception $e){
            $response = [
                "error" => "File doesn`t exists"
            ];
            $statusCode = 404;
        }finally{
            return Response::json($response, $statusCode);
        }*/
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
