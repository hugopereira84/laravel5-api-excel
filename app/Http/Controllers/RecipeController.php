<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Http\Requests;

//model of recipe
use App\Recipe;


class RecipeController extends Controller
{
    /*****************************************/
    //http://www.sitepoint.com/build-rest-resources-laravel//
    //http://code.tutsplus.com/tutorials/laravel-4-a-start-at-a-restful-api-updated--net-29785//
    //http://stackoverflow.com/questions/27940690/laravel-routing-does-not-work-with-post

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

        }catch (\Exception $e){
            $statusCode = 400;
        }finally{
            return Response::json($response, $statusCode);
        }
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeInfo(Request $request)
    {
        echo 'nix, sera';die();
        $recipe = new Recipe();
        $recipe->box_tye = 'vegetarian';
        $recipe->title = 'teste';
        $recipe->slug = 'teste';
        $recipe->short_title = 'vegetarian';

        $recipe->save();

        // Validation and Filtering is sorely needed!!
        // Seriously, I'm a bad person for leaving that out.

        /*

        return Response::json(array(
            'error' => false,
            'urls' => $urls->toArray()),
            200
        );*/
    }





    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        try{
            $data = Recipe::find($id);

            $statusCode = 200;
            $response = [ "recipe" => $data];

        }catch(\Exception $e){
            $response = [
                "error" => $e->getMessage()
            ];
            $statusCode = 404;
        }finally{
            return Response::json($response, $statusCode);
        }
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
