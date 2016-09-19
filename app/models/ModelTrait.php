<?php namespace Models;

use Input, Schema, Response;

trait ModelTrait {

    public function traitDelete($id)
    {
        
         $model = $this->find($id);
         var_dump($model);

        return Response::json($id, 200);

        if($model == NULL)
        {
            return Response::json([
                'error_code' => 1, 
                'error_description' => $this->notFoundMessage
                ], 404); // 403
        }

        $model->delete();

        return Response::json(NULL, 200);
    }

}