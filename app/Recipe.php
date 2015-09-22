<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

//libs of excel
use League\Csv\Reader;



class Recipe extends Model
{

    private static $lstFieldsModel = ['id',
        'created_at',
        'updated_at',
        'box_type',
        'title',
        'slug',
        'short_title',
        'marketing_description',
        'calories_kcal',
        'protein_grams',
        'fat_grams',
        'carbs_grams',
        'bulletpoint1',
        'bulletpoint2',
        'bulletpoint3',
        'recipe_diet_type_id',
        'season',
        'base',
        'protein_source',
        'preparation_time_minutes',
        'shelf_life_days',
        'equipment_needed',
        'origin_country',
        'recipe_cuisine',
        'in_your_box',
        'gousto_reference'];

    /**
     * Read excel files to all functions in this model
     *
     * @return mixed
     */
    private static function loadXlsFile(){

        //read exel files
        $inputCsv = Reader::createFromPath(storage_path() . '/recipes.csv');
        $inputCsv->setDelimiter("\t");
        return $inputCsv;
    }


    public static function find($id, $columns = ['*']) {
        $extraParams = array('id'=>$id, 'columns'=>$columns);
        $csv = self::loadXlsFile();

        $arrNameFieldsCsv = [];
        if($columns == ['*']){
            $arrNameFieldsCsv = self::$lstFieldsModel;
        }else{
            $fieldsNotExists = [];
            $isValid = 1;
            //validate all columns exists
            foreach($columns as $column){
                if(!in_array($column, self::$lstFieldsModel)){
                    $isValid = 0;
                    $fieldsNotExists[] = $column;
                }
            }

            if($isValid == 0){
                throw new \Exception('Fields does not exist: '.implode(',',  $fieldsNotExists));
            }

            $arrNameFieldsCsv = $columns;
        }


        $result = $csv->addFilter(function ($row) use ($extraParams) {
            //column zero, because it is the number of column id
            $id = (int)$extraParams['id'];
            return $id == $row[0]; //we are looking for the year 2010
        })->fetchAssoc($arrNameFieldsCsv);

        return $result[0];
    }

    public function save(array $options = [])
    {
        $fieldsToSave = $this->toArray();

        var_dump($fieldsToSave);
    }

}
