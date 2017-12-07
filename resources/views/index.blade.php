@extends('layouts.app')

@push('head_styles')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
@endpush

@push('head_scripts')
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
 @endpush

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading"><h4>Dashboard</h4></div>
                    <div class="panel-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                {{ session('success') }}
                            </div>
                        @endif

                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="post" id="todoForm" action="{{ url('/add-todo') }}">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-briefcase"></span> </span>
                                            <textarea class="form-control" name="todo" id="todo" placeholder="Todo, task etc.">{{ old('todo') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group">
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> </span>
                                            <input type="text" class="form-control" name="todo_date" id="todo_date" placeholder="Date / Deadline" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="submit" class="btn btn-primary btn-lg" value="Add Todo" id="add_todo_btn" />
                                    </div>
                                </div>
                                {{ csrf_field() }}
                            </form>
                            <hr/>


                            <div class="row" id="todos">
                                <div class="col-md-12">
                                    {{ $todos->links() }}

                                    <?php $filter_param = Route::input('filter'); ?>
                                    <div class="pull-left">
                                        <div class="dropdown">
                                            <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                Filter
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu filter-menu" id="{{ $filter_param }}" aria-labelledby="dropdownMenu1">
                                                <li><a href="{{ route('filter', ['filter' => 'all']) }}"><span class="glyphicon glyphicon-ok filter-icon @if($filter_param == 'all' || $filter_param == '') filter-first @endif" aria-hidden="true"></span> All</a></li>
                                                <li><a href="{{ route('filter', ['filter' => 'completed']) }}"><span class="glyphicon glyphicon-ok filter-icon @if($filter_param == 'completed') filter-first @endif" aria-hidden="true"></span> Completed</a></li>
                                                <li><a href="{{ route('filter', ['filter' => 'uncompleted']) }}"><span class="glyphicon glyphicon-ok filter-icon @if($filter_param == 'uncompleted') filter-first @endif" aria-hidden="true"></span> Uncompleted</a></li>
                                                <li><a href="{{ route('filter', ['filter' => 'due-date']) }}"><span class="glyphicon glyphicon-ok filter-icon @if($filter_param == 'due-date') filter-first @endif" aria-hidden="true"></span> With Due Date</a></li>
                                                <li><a href="{{ route('filter', ['filter' => 'no-due-date']) }}"><span class="glyphicon glyphicon-ok filter-icon @if($filter_param == 'no-due-date') filter-first @endif" aria-hidden="true"></span> Without Due Date</a></li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="pull-right">
                                        Results per page:
                                        <form method="post" id="updatePaginationForm" action="{{ route('pagination', [ 'id' => Auth::user()->id ]) }}">
                                        <select name="pagination" id="pagination">
                                            <option value="5" @if(Auth::user()->pagination == 5) selected @endif>5</option>
                                            <option value="10" @if(Auth::user()->pagination == 10) selected @endif>10</option>
                                            <option value="20" @if(Auth::user()->pagination == 20) selected @endif>20</option>
                                            <option value="30" @if(Auth::user()->pagination == 30) selected @endif>30</option>
                                            <option value="50" @if(Auth::user()->pagination == 50) selected @endif>50</option>
                                            <option value="100" @if(Auth::user()->pagination == 100) selected @endif>100</option>
                                        </select>
                                            {{ method_field("PUT") }}
                                            {{ csrf_field() }}
                                        </form>
                                    </div>
                                    <div class="clearfix"></div>
                                    <br />
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="todos-table">
                                            <thead>
                                            <tr class="bg-info">
                                                <th width="15%"><input type="checkbox" class="check-all" /> Complete All</th>
                                                <th width="45%">Todo</th>
                                                <th width="15%">Due Date</th>
                                                <th width="15%">Completed Date</th>
                                                <th width="10%"><input type="button" class="btn btn-danger btn-xs pull-right" id="delete-all" value="Delete All"></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if(count($todos) > 0)
                                                @foreach($todos as $todo)
                                                    <tr id="todo{{ $todo['id'] }}" @if($todo['completed'] == 1) class="completed-todo" @endif>
                                                        <td><input type="checkbox" name="completed" class="completed" @if($todo['completed'] == 1)  checked @endif /></td>
                                                        <td><div class="todo-text">{{ $todo['todo'] }}</div></td>
                                                        <td><div class="todo-date" data-toggle="tooltip" data-placement="top" title="{{ !empty($todo['todo_date']) ? date('F j, Y', strtotime($todo['todo_date'])) : '' }}">{{ $todo['todo_date'] }}</div></td>
                                                        <td><div class="todo-completed-date" data-toggle="tooltip" data-placement="top" title="{{ !empty($todo['completed_date']) ? date('F j, Y', strtotime($todo['completed_date'])) : '' }}">@if($todo->completed_date != '0000-00-00') {{ $todo->completed_date }} @endif</div></td>
                                                        <td><div class="btns-cell"><input type="button" class="btn btn-info btn-xs edit-todo @if($todo['completed'] === true) display @endif" value="Edit"> <input type="button" class="btn btn-danger btn-xs delete-todo" value="Delete" /></div></td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" data-keyboard="false" data-backdrop="static" role="dialog" id="editTodoModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-default">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Todo</h4>
                </div>
                <div class="modal-body">
                    <div class="modal-msg"></div>
                    <form method="post" id="updateTodoForm" action="{{ url('/update-todo') }}">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-briefcase"></span> </span>
                                    <textarea class="form-control" name="todo" id="u_todo" placeholder="Todo, task etc."></textarea>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> </span>
                                    <input type="text" class="form-control" name="todo_date" id="u_todo_date" placeholder="Date / Deadline"  />
                                </div>
                            </div>
                            <div class="col-md-3"><input type="button" class="btn btn-primary btn-lg" value="Update" id="update_todo_btn" /></div>
                            <input type="hidden" name="update_todo_id" id="update_todo_id" value="" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
@endsection

@push('footer_scripts')
    <script src="{{ asset('js/jquery-ui.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
@endpush
