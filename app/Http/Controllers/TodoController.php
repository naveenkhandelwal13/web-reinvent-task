<?php

namespace App\Http\Controllers;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ToDoResource;
use App\Models\Todo;



class TodoController extends BaseController
{

    public const DEFAULT_PER_PAGE = 10;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): JsonResponse
    {

        $query = Todo::query();
        $perPage = $request->input('perPage');

        if (isset($perPage) && $perPage !== '') {
            $per_page = intval($perPage);
        } else {
            $per_page = self::DEFAULT_PER_PAGE;
        }
        if ($request->has('list_type')) {

            if ($request->input('list_type') == 'only_trashed') {
                $query->onlyTrashed();
            }
            elseif ($request->input('list_type') == 'with_trashed') {
                $query->withTrashed();
            }

        }
        else{
            $query->paginate($per_page);
        }
        $todoList = $query->orderBy('id', 'desc')->get();
        
        $loadView = view('tasks.table')->with(['tasks' => $todoList])->render();
        return $this->sendSuccess($loadView, __('todo.list'));

    }


    public function home(){
        return view('welcome');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {

             #validate
             $validatedData = Validator::make($request->all(), $this->getValiditionRule());

             #check validation
             if ($validatedData->fails()) {
                 return $this->sendError(__('common.validaton_error'), $validatedData->errors());
             }

            Todo::create($request->all());
            return redirect()->route('list.index');
            
        } catch (Exception $e) {
            if ($e->errorInfo[1] == 1062) { // MySQL error code for duplicate entry
                return $this->sendDuplicate([], __('todo.duplicate'));
            }
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }



    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function show(Todo $todo)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function edit(Todo $todo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Todo $todo)
    {
        try {
            $todo->status = $request->input('status');
            $todo->save();
            return redirect()->route('list.index');
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Todo $todo)
    {
        try {
            $todo->status = '0';
            $todo->save();
            $todo->delete();

            return redirect()->route('list.index');
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
        //
    }

    
    private function getValiditionRule()
    {
        return [
            'task_name' => ['required']
        ];
    }


}
