<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Task;
use Illuminate\Http\Request;

class CommentController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $task = Task::findOrFail($request->task_id);

        if (auth()->id() != $task->user_id){
            return response()->json(['message' => 'Error , permission denied'], 401);
        }

        $rules = $request->validate([
            'task_id' => 'required',
            'body' => 'required'
        ]);

        if (auth()->user()->comments()->create($request->all())){
            return ['message' => 'Comment created successfully'];
        }

        return response()->json(['message' => 'There is an error , please try again later'], 500);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        //
        $task = Task::findOrFail($request->task_id);

        if (auth()->id() != $task->user_id || auth()->id()!= $comment->user_id){
            return response()->json(['message' => 'Error , permission denied'], 401);
        }

        $rules = $request->validate([
            'task_id' => 'required',
            'body' => 'required'
        ]);


        if ($comment->update($request->all())){
            return ['message' => 'Comment updated successfully'];
        }

        return response()->json(['message' => 'There is an error , please try again later'], 500);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        //
        if (auth()->id() != $comment->user_id){
            return response()->json(['message' => 'Error , permission denied'], 401);
        }

        if ($comment->delete()){
            return ['message' => 'Comment has been deleted successfully'];
        }
        return response()->json(['message' => 'There is an error , please try again later'], 500);
    }
}
