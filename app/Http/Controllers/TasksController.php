<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;    // 追加
use App\User;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {  $data = [];
        if (\Auth::check()) {
            $user = \Auth::user();
            $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);
            
            $data = [
               
                'tasks' => $tasks,
            ];
        }
        
        return view('welcome', $data);
             
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $task = new Task;

        return view('tasks.create', [
            'task' => $task,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $this->validate($request, [
            'status' => 'required|max:10',
            'content' => 'required|max:191',
        ]);
        
        
        $task = new Task;
        $task->user_id = \Auth::id();
        $task->status = $request->status;
        $task->content = $request->content;
        $task->save();
        
        
        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    function show($id)
    {
        $task = Task::find($id);
        
        //タスクを作った人のuserId
        $taskUserId = $task->user_id;
        //ログインしている人のuserId
        $loginUserId = \Auth::id();
        if($taskUserId != $loginUserId){
            //トップに飛ばす
            return redirect('/');
        }

        return view('tasks.show', [
            'task' => $task,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    function edit($id)
    {
        $task = Task::find($id);
        //タスクを作った人のuserId
        $taskUserId = $task->user_id;
        //ログインしている人のuserId
        $loginUserId = \Auth::id();
        if($taskUserId != $loginUserId){
            //トップに飛ばす
            return redirect('/');
        }

        return view('tasks.edit', [
            'task' => $task,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    function update(Request $request, $id)
    {
        $this->validate($request, [
            'status' => 'required|max:10',
            'content' => 'required|max:191',
        ]);

        $task = Task::find($id);
        //タスクを作った人のuserId
        $taskUserId = $task->user_id;
        //ログインしている人のuserId
        $loginUserId = \Auth::id();
        if($taskUserId != $loginUserId){
            //トップに飛ばす
            return redirect('/');
        }
            
        $task = Task::find($id);
        $task->user_id = \Auth::id();
        $task->status = $request->status;
        $task->content = $request->content;
        $task->save();

        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    function destroy($id)
    {
        $task = Task::find($id);
        //タスクを作った人のuserId
        $taskUserId = $task->user_id;
        //ログインしている人のuserId
        $loginUserId = \Auth::id();
        if($taskUserId != $loginUserId){
            //トップに飛ばす
            return redirect('/');
        }
        
        $task = Task::find($id);
        $task->delete();

        return redirect('/');
    }
    
}