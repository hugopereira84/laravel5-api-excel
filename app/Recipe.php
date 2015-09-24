<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

//libs of excel
use League\Csv\Reader;
use League\Csv\Writer;



class Recipe extends Model
{
    public $timestamps = false;
    
    private static $fieldsSave =  [];
    private static $lstFieldsModel = [
        'id',
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
    private static function loadXlsFile($typeRead){
        //read exel files
        $csvReader = Reader::createFromPath(storage_path() . '/recipes.csv');
        $csvReader->setDelimiter("\t");
        
        $csvWriter = Writer::createFromPath(storage_path() . '/recipes.csv', 'a');
        $csvWriter->setDelimiter("\t");
        
        $reader = array('reader'=>$csvReader, 'writer'=>$csvWriter);
        
        return $reader[$typeRead];
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
        $csv            = self::loadXlsFile('reader');
        
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
        $offset = isset($offsetHeader) && !empty($offsetHeader) ? $offsetHeader : 1;
        
        $limitHeader = $request->header('limit');
        $limit = isset($limitHeader) && !empty($limitHeader) ? $limitHeader : 5;
        
        
        $csv = self::loadXlsFile('reader');
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
    
    public function setFieldsSave($arrInfFields){
        self::$fieldsSave = $arrInfFields;
    }
    public function getFieldsSave(){
        return self::$fieldsSave;
    }
    public function save(array $options = [])
    {   
        //Validate number fields
        $fieldsModel    = self::getFieldsModel();
        $numberFields = count($fieldsModel);
       
        
        $csvWriter = self::loadXlsFile('writer');
        $csvReader = self::loadXlsFile('reader');

        
        //GET LAST ID
        $csvReader->addFilter(function ($row) {
                        return isset($row[0], $row[1], $row[2], $row[3], $row[4],
                                    $row[5], $row[6], $row[7], $row[8], $row[9],
                                    $row[10], $row[11], $row[12], $row[13], $row[14],
                                    $row[15], $row[16], $row[17], $row[18], $row[19],
                                    $row[20], $row[21], $row[22], $row[23], $row[24],
                                    $row[25]);
                    });
        $data = $csvReader->fetchAssoc($fieldsModel);
        $idToBeInserted = count($data);
        
        //insert missing fields 
        $this->id = $idToBeInserted;
        $this->created_at = date('d/m/Y H:i:s');
        $this->updated_at = date('d/m/Y H:i:s'); 
        foreach($this->getFieldsSave() as $key => $value){
            $this->$key = $value;
        }
            
        
        $result = 'OK';
        try{
            //validators
            $csvWriter->addValidator(function (array $row) use($numberFields) {
                return $numberFields == count($row);
            }, 'row_must_contain_'.$numberFields.'_cells');

            $csvWriter->insertOne($this->toArray());
        } catch (Exception $ex) {
            $result = 'KO';
        }
        
        return $result;
    }

}
