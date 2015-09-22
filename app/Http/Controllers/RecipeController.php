<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Http\Requests;

//model of recipe
use App\Recipe;

//create pagination
use Illuminate\Pagination;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

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
    public function index(Request $request)
    {
        
        try{
            $data = Recipe::allWithPagination($request);
           
            $statusCode = 200;
            $response = [ "recipes" => $data, '_token'=>csrf_token()];
            
        }catch (\Exception $e){
            $response = [
                "error" => $e->getMessage()
            ];
            $statusCode = 404;
        }finally{
            return Response::json($response, $statusCode);
        }
    }
    
    public function listByfield(Request $request, $nameField, $valueField){
        try{
            $extraParams = array('nameField' => $nameField, 'valueField' => $valueField);
            $data = Recipe::allWithPagination($request, $extraParams);
           
            $statusCode = 200;
            $response = [ "recipe" => $data, '_token'=>csrf_token()];

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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        
        $recipe = new Recipe();
        $lstFields = $recipe::getFieldsModel();
        foreach($lstFields as $field){
            $recipe->$field = $request->input($field);
        }
        
        

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
     * token that is going to be used in post method
     * 
     * @return array with token string
     */
    public function getToken()
    {
        try{
            $statusCode = 200;
            $response = [ '_token'=>csrf_token()];
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
            $response = [ "recipe" => $data, '_token'=>csrf_token()];

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
