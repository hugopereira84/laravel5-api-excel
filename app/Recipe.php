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

    public static function getFieldsModel()
    {
        return self::$lstFieldsModel;
    }
    /**
     * Read excel files to all functions in this model
     *
     * @return mixed
     */
    private static function loadXlsFile(){
        //read exel files
        $inputCsv = Reader::createFromPath(storage_path() . '/recipes.csv');
        $inputCsv->setDelimiter("\t");
        //$inputCsv->setOffset(1);
        return $inputCsv;
    }
    
    private static function modelFieldsExists($columns){
        $fieldsModel    = self::getFieldsModel();
        
        $arrNameFieldsCsv = [];
        if($columns == ['*']){
            $arrNameFieldsCsv = $fieldsModel;
        }else{
            $fieldsNotExists = [];
            $isValid = 1;
            //validate all columns exists
            foreach($columns as $column){
                if(!in_array($column, $fieldsModel)){
                    $isValid = 0;
                    $fieldsNotExists[] = $column;
                }
            }

            if($isValid == 0){
                throw new \Exception('Fields does not exist: '.implode(',',  $fieldsNotExists));
            }

            $arrNameFieldsCsv = $columns;
        }
        
        return $arrNameFieldsCsv;
    }

    public static function find($id, $columns = ['*']) {
        $extraParams    = array('id'=>$id, 'columns'=>$columns);
        $csv            = self::loadXlsFile();
        
        
        
        $arrNameFieldsCsv = self::modelFieldsExists($columns);
        

        $result = $csv->addFilter(function ($row) use ($extraParams) {
            //column zero, because it is the number of column id
            $id = (int)$extraParams['id'];
            return $id == $row[0]; 
        })->fetchAssoc($arrNameFieldsCsv);

        return count($result) == 1 ? reset($result) : [];
    }

    public static function allWithPagination($request, $extraParams = NULL, $columns = ['*'])
    {
        //FILTER FIELDS BY SOMETHING
        $nameField = $extraParams['nameField'];
        $valueField = $extraParams['valueField'];
        
        
            
        $offsetHeader = $request->header('offset');
        $offset = isset($offsetHeader) && !empty($offsetHeader) ? $offsetHeader : 0;
        
        $limitHeader = $request->header('limit');
        $limit = isset($limitHeader) && !empty($limitHeader) ? $limitHeader : 5;
        
        
        $csv = self::loadXlsFile();
        //$csv->setOffset(1);
        
        $arrNameFieldsCsv = self::modelFieldsExists($columns);
        
        

        
        //Validate that row of csv has all values
        $csv->addFilter(function ($row) {
                        return isset($row[0], $row[1], $row[2], $row[3], $row[4],
                                    $row[5], $row[6], $row[7], $row[8], $row[9],
                                    $row[10], $row[11], $row[12], $row[13], $row[14],
                                    $row[15], $row[16], $row[17], $row[18], $row[19],
                                    $row[20], $row[21], $row[22], $row[23], $row[24],
                                    $row[25]);
                    });
                    
        //make a where in the csv file
        if(!empty($nameField) && !empty($valueField)){
            $lstFieldsModel = self::getFieldsModel();
            $keyValueSelected = array_search($nameField, $lstFieldsModel);
            $extraParams['keyValueSelected'] = $keyValueSelected;
            $csv->addFilter(function ($row) use ($extraParams) {
                return (string)$extraParams['valueField'] == (string)$row[$extraParams['keyValueSelected']];
            });
        }

        //make pagination
        $csv->setOffset($offset);
        $csv->setLimit($limit);

        //get results
        $result = $csv->fetchAssoc($arrNameFieldsCsv);

                  
        return $result;
    }
    
    public function save(array $options = [])
    {
        $fieldsToSave = $this->toArray();

        var_dump($fieldsToSave);
    }

}
