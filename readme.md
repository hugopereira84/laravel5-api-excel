## Laravel PHP Framework api with csv as database

This api was created using laravel, because the implementation of this api using other framework would take more time 
to do it and also because is very accessible and powerfull to use laravel.

This api also can restrict the name fields that are returned in the api, but this functionality has not been implemented in the controller. 
Only on Model. 


## Using api

Resources: <br><br>
>**RECIPE**:<br>
    api/recipe/gettoken [GET], get token to be used in post and put methods<br>
    api/recipe [GET], list all recipes without any filter and with pagination (limit and offset are the headers parameters used to created pagination)<br>
    api/recipe/{id} [GET], return information of recipe, by it's id selected<br>
    api/recipe/{name_field}/{value_field} [GET], returns recipes filtered by any name field and value fields selected<br>
    api/recipe [POST], used to creat a recipe<br>
    api/recipe/{id} [PUT], used to update any recipe by it's id
    
>>**Fields**:     
{id}, id of recipe<br>
{name_field}, any field name of recipe model<br>
{value_field}, any field value that a person wants to search
 
### License

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
