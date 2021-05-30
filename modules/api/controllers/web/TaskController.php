<?php

namespace app\modules\api\controllers\web;

use app\modules\api\controllers\DefaultController;
use app\modules\api\models\Task;
use app\modules\api\models\Property;
use app\modules\api\services\TaskService;
use Yii;

class TaskController extends DefaultController
{
    public function actionIndex()
    {
        $property_id = Yii::$app->request->get('property_id');
        $property    = Property::findOne($property_id);
        $tasks       = $property->tasks;

        return [
            'code'  => $this->response_code['request_success'],
            'tasks' => $tasks,
        ];
    }

    public function actionCreate()
    {
        $task = TaskService::createOrUpdate(Yii::$app->request->post());

        if ($task) {
            $task_res = Task::findOne($task->id);
            return [
                'code' => $this->response_code['request_success'],
                'task' => $task_res,
            ];
        }
        return [
            'code' => $this->response_code['request_invalid'],
            'task' => $task->errors,
        ];
    }

    public function actionUpdate($id)
    {
        $edit_task = Task::findOne($id);

        if (!$edit_task) {
            return [
                'code'    => $this->response_code['request_invalid'],
                'message' => 'Items are not found',
            ];
        }

        $task = TaskService::createOrUpdate(Yii::$app->request->getBodyParams(), $edit_task);

        if ($task) {
            $task_res = Task::findOne($task->id);
            return [
                'code' => $this->response_code['request_success'],
                'task' => $task_res,
            ];
        }
        return [
            'code' => $this->response_code['request_invalid'],
            'task' => $task->errors,
        ];
    }
    public function actionDelete($id)
    {
        $task = Task::findOne($id);
        if ($task) {
            $task->delete();
            return [
                'code' => $this->response_code['request_success'],
            ];
        }

        return [
            'code'    => $this->response_code['request_failed'],
            'message' => 'Item is not found',
        ];
    }

    public function actionGetBoard(){
      $property_id = Yii::$app->request->get('property_id');
      $property    = Property::findOne($property_id);
      $tasks       = array(
        'todo' => [],
        'underway' => [],
        'completed' => []
      );
      foreach ($property->tasks as $key => $task) {
        $tasks[$task->status][] = array(
          'id' => (string)$task->id,
          'title' => $task->title,
          'description' => $task->details, 
          'draggable' => true          
        );
      }
      
      $data = array(
        'lanes' => array(
          array(
            'id' => 'todo',
            'title' => 'To Do',
            'cards'=> $tasks['todo']
          ),
          array(
            'id' => 'underway',
            'title' => 'Underway',
            'cards'=> $tasks['underway']
          ),
          array(
            'id' => 'completed',
            'title' => 'Completed',
            'cards'=> $tasks['completed']
          )
        )
      );

      return [
          'code'  => $this->response_code['request_success'],
          'data' => $data,
      ];
    }

    public function actionChangeStatus($id){
      $task = Task::findOne($id);
      if (!$task) {
          return [
              'code'    => $this->response_code['request_invalid'],
              'message' => 'Items are not found',
          ];
      }
      $status = Yii::$app->request->getBodyParams()['task']['status'];
      $task->status = $status;
      $task->update();
    
      return [
          'code'  => $this->response_code['request_success']
      ];
    }
}
