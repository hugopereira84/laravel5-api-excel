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
        try{
            
            $fieldsNotToSet = ['id', 'created_at', 'updated_at'];
            $recipe = new Recipe();
            $lstFields = $recipe::getFieldsModel();
           
            $arrInfFields = [];
            foreach($lstFields as $field){
                if(in_array($field, $fieldsNotToSet)){
                    continue;
                }
                $valueField = $request->input($field);
                $arrInfFields[$field] = isset($valueField) ? $request->input($field) : "" ;
            }
            $recipe->setFieldsSave($arrInfFields);
            $data = $recipe->save();
            
            $statusCode = 200;
            $response = [ "result" => $data, '_token'=>csrf_token()];
            
        }catch (\Exception $e){
            $response = [
                "error" => $e->getMessage()
            ];
            $statusCode = 404;
        }finally{
            return Response::json($response, $statusCode);
        }
        

        
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
        try{
            $allReqInf = $request->all();



            //validate resource exists
            if(isset($allReqInf['id'])){
                $resultJson = $this->show($allReqInf['id']);
                $resultArr = json_decode($resultJson->content(), 'true');
                if(array_key_exists('error', $resultArr) ){
                    throw new \Exception('Error reading information');
                }
                if(count($resultArr['recipe']) == 0){
                    throw new \Exception('Resource does not exist');
                }
            }else{
                throw new \Exception('Resource does not exist');
            }




            //update recipe
            $recipe = new Recipe();
            $lstFields = $recipe::getFieldsModel();



            $arrInfFields = [];
            foreach($lstFields as $field){
                if(array_key_exists ( $field , $allReqInf )){
                    $arrInfFields[$field] = $request->input($field);
                }
            }


            $result = $recipe->update($arrInfFields);


            $statusCode = 200;
            $response = [ "result" => $result, '_token'=>csrf_token()];

        }catch (\Exception $e){
            $response = [
                "error" => $e->getMessage()
            ];
            $statusCode = 404;
        }finally{
            return Response::json($response, $statusCode);
        }
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
