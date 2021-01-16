<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\FileResource;
use App\Models\File;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function upload(Request $request , $taskId){

        $task = Task::findOrFail($request->task_id);

        if (auth()->id() != $task->user_id) {
            return response()->json(['message' => 'Error , permission denied'], 401);
        }

        $rules = $request->validate([
            'file' => 'required|max:9000|mimes:jpe,jpeg,pdf,png'
        ]);

        $fileName = $request->file('file')->hashName();
        $uploaded = $request->file('file')->storeAs('public/tasks/'.$task->id , $fileName);
        if ($uploaded){

            $fileData = [
                'user_id'=> auth()->id(),
                'name' => $fileName

            ];

            $saveFile = $task->files()->create($fileData);

            if ($saveFile){
                return new FileResource($saveFile);
            }

        }


        return response()->json(['message' => 'There is an error , please try again later'], 500);
    }

    public function destroy(File $file)
    {

        if (auth()->id() != $file->user_id) {
            return response()->json(['message' => 'Error , permission denied'], 401);
        }

        if ($file->delete()){
           $deleted = Storage::delete('public/tasks/'.$file->task_id.'/'.$file->name);
           if ($deleted){
               return ['message' => 'File deleted successfully'];
           }
        }

        return response()->json(['message' => 'There is an error , please try again later'], 500);



    }

}
