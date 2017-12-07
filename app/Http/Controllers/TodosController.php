<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteTodo;
use App\Http\Requests\StoreTodo;
use App\Http\Requests\UpdateTodo;
use App\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodosController extends Controller
{

    public function index(Request $request)
    {
        $todos = $this->get_all();
        return view('index', compact('todos'));
    }

    public function add(StoreTodo $request)
    {
        $todo = new Todo();
        $todo->user_id = Auth::user()->id;
        $todo->todo = $request->todo;
        $todo->todo_date = $request->todo_date;
        $todo->save();

        return redirect()->back()->with('success', 'Todo was added successfully.');
    }

    public function completed(Request $request, $id)
    {
        if($request->ajax())
        {
            $todo = Todo::find($id);
            if($todo->user_id === Auth::user()->id)
            {
                $todo->completed = $request->completed;
                $todo->completed_date = date('Y-m-d');
                $todo->save();
            }

            return response()->json(['success' => true]);
        }
    }

    public function update(UpdateTodo $request, $id)
    {
        if($request->ajax())
        {
            $todo = Todo::find($id);
            $todo->todo = $request->todo;
            $todo->todo_date = $request->todo_date;
            $todo->save();
            return response()
                ->json(['success' => true]);
        }
    }

    public function delete(Request $request, $id)
    {
        if($request->ajax())
        {
            $todo = Todo::find($id);
            if($todo->user_id === Auth::user()->id)
            {
                $todo->delete();
                return response()
                    ->json(['success' => true]);
            }
            else
            {
                return response()->json(['success' => false]);
            }
        }
    }

    public function delete_all(Request $request)
    {
        if($request->ajax())
        {
            $delete = Todo::where('user_id', '=', Auth::user()->id)->delete();
            return response()->json(['success' => true]);
        }
    }

    public function filter($filter = 'all')
    {
        switch($filter)
        {
            case 'all':
                $todos = Todo::paginate(Auth::user()->pagination);
                break;

            case 'completed':
                $todos = Todo::where('completed', '=', 1)->paginate(Auth::user()->pagination);
                break;

            case 'uncompleted':
                $todos = Todo::where('completed', '=', 0)->paginate(Auth::user()->pagination);
                break;

            case 'due-date':
                $todos = Todo::where('todo_date', '!=', 'NULL')->orderBy('todo_date', 'ASC')->paginate(Auth::user()->pagination);
                break;

            case 'no-due-date':
                $todos = Todo::where('todo_date', '=', 'NULL')->paginate(Auth::user()->pagination);
                break;

            default:
                $todos = Todo::paginate(Auth::user()->pagination);
                break;
        }

        return view('index', compact('todos'));
    }

    private function get_all()
    {
        $todos = Auth::user()->todos()->paginate(Auth::user()->pagination);
        return $todos;
    }

}
