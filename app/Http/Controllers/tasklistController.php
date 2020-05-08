<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class tasklistController extends Controller
{
     public function index()
    {
        $data = [];
        if (\Auth::check()) {
            $user = \Auth::user();
            $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);
            
            $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];
        }
        
        return view('welcome', $data);
    }
    
    public function store(Request $request)
    {
        $this->validate($request, [
            'content' => 'required|max:191',
        ]);

        $request->user()->tasklist()->create([
            'content' => $request->content,
        ]);

        return back();
    }
    
     public function destroy($id)
    {
        $tasklist = \App\tasklist::find($id);

        if (\Auth::id() === $tasklist->user_id) {
            $tasklist->delete();
        }

        return back();
    }
}
