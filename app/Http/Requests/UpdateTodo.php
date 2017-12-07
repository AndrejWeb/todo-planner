<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Todo;
use Illuminate\Support\Facades\Auth;


class UpdateTodo extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $todo = Todo::find($this->route('id'));
        return $todo->user_id === Auth::user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'todo' => 'required',
            'todo_date' => 'nullable|date_format:Y-m-d',
        ];
    }
}
