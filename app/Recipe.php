<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Facades\Excel;



class Recipe extends Model
{

    /**
     * Read excel files to all functions in this model
     *
     * @return mixed
     */
    private static function loadXlsFile(){

        //read exel files
        return Excel::load(storage_path() . '/recipes.csv', function($reader) {});
    }

    public static function find($id, $columns='*') {
        $file = self::loadXlsFile();
        foreach($file->get() as $row){
            var_dump($row);
        }
        /*echo '<pre>';
        var_dump(get_class_methods($file));
        exit();*/


    }

}
