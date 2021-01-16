<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Models\Category;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{

    public function __construct(){
        $this->middleware('auth:api');

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $tasks = auth()->user()->tasks()->with('category')->paginate(100);
        return  TaskResource::collection($tasks);

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
        //
        $category = Category::findOrFail($request->category_id);

        if (auth()->id() != $category->user_id){
            return response()->json(['message' => 'Error , permission denied'], 401);
        }

        $rules = $request->validate([
            'title' => 'required',
            'category_id' => 'required',
            'due_date' => 'required|date|date_format:Y-m-d|after_or_equal:'.date('Y-m-d'),
        ]);

        if (auth()->user()->tasks()->create($request->all())){
            return ['message' => 'Task created successfully'];
        }

        return response()->json(['message' => 'There is an error , please try again later'], 500);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        //

        if (auth()->id() != $task->user_id) {
            return response()->json(['message' => 'Error , permission denied'], 401);
        }

        $task->load('category' , 'comments' , 'files');
        return new TaskResource($task);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        //
        $category = Category::findOrFail($request->category_id);

        if (auth()->id() != $category->user_id || auth()->id()!= $task->user_id){
            return response()->json(['message' => 'Error , permission denied'], 401);
        }

        $rules = $request->validate([
            'title' => 'required',
            'category_id' => 'required',
            'due_date' => 'required|date|date_format:Y-m-d',
        ]);


        if ($task->update($request->all())){
            return ['message' => 'Task updated successfully'];
        }

        return response()->json(['message' => 'There is an error , please try again later'], 500);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        //

        if (auth()->id() != $task->user_id){
            return response()->json(['message' => 'Error , permission denied'], 401);
        }

        if ($task->delete()){
            return ['message' => 'Task has been hidden successfully'];
        }
        return response()->json(['message' => 'There is an error , please try again later'], 500);
    }

    public function restore($taskId)
    {
        //

        $task = Task::withTrashed()->findOrFail($taskId);

        if (auth()->id() != $task->user_id){
            return response()->json(['message' => 'Error , permission denied'], 401);
        }

        if ($task->restore()){
            return ['message' => 'Task restored successfully'];
        }

        return response()->json(['message' => 'There is an error , please try again later'], 500);
    }

    public function forceDelete($taskId)
    {
        //

        $deletedTask = Task::withTrashed()->findOrFail($taskId);

        if (auth()->id() != $deletedTask->user_id){
            return response()->json(['message' => 'Error , permission denied'], 401);
        }

        if ($deletedTask->forceDelete()){
            Storage::deleteDirectory('public/tasks/'.$deletedTask->id);
            return ['message' => 'Task Deleted successfully'];
        }

        return response()->json(['message' => 'There is an error , please try again later'], 500);
    }

}
