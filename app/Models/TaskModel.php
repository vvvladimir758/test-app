<?php 
namespace App\Models;


class TaskModel extends BaseModel{
    
    protected static $pageLimit = 3;
    protected static $table = 'tasks';
    
   
    
    public static function tasks($params){
        
       
        if(!empty($params[1]) && !empty($params[2]) ){
            list($page,$column,$type) = [$params[0],$params[1],$params[2]];
        }
        else{
            list($page,$column,$type) = ['0','users.id','desc'];
        }
        
        if(isset($params[0])){
            $page = $params[0]-1;
        }
        
        $offset = $page*self::$pageLimit;
     
        $order =  $type =='desc' ? 'order_by_desc' : 'order_by_asc' ;
     
        $tasks = \ORM::for_table(self::getTable())
        ->select( self::getTable().'.id', 'task_id')
        ->select_many('name','descripition','status')
        ->join('users', array(self::getTable().'.user_id', '=', 'users.id'))
        ->$order($column)
        ->limit(self::$pageLimit)
        ->offset($offset)
        ->find_many();
      
       
       
        return $tasks;
    }
    
    public static function tasksPageCount(){
        $tasks = \ORM::for_table('tasks')->count();
        
        return range(1,(ceil($tasks/self::$pageLimit)));
        
    }
    
    public static function store($data){
        
        $userId = UserModel::create($data);
        
        $task = \ORM::for_table('tasks')->create();
        $task->user_id = $userId;
        $task->descripition = $data['descripition'];
        $task->status = 0;
        $task->save();
        
        return true;
    }
    
    
    
}