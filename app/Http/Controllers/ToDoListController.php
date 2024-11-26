<?php

namespace App\Http\Controllers;

use App\Http\Resources\ToDoListResource;
use App\Models\ToDoList;
use Illuminate\Http\Request;

class ToDoListController extends Controller
{
    public function search(){
        $search = request('search');
        return  ToDoList::select('id','title')
          -> where('to_do_lists.title' , 'like' , "%$search%")
          ->get();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request('search');
        $query = ToDoList::orderByDesc('id');
        if($search){
            $query->where('to_do_lists.title','Ilike', "%$search%");
        }
        $data = ToDoListResource::collection($query->get());

        return $data;

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(!$request->title)
        return response()->json(['message' => 'title kiritilmadi'],400);
       ToDoList::create([
        'title' => $request->title
       ]);
       return response()->json(['message' => 'Ok'],201);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
       $todolist =  ToDoList::where('id', $id)->first();
         if(!$todolist){
            return response()->json(['message' => 'Malumot topilmadi!'],404);
         }
       $todolist -> update([
           'title' => $request->title ?? $todolist->title
       ]);
       return response()->json(['message' => 'Malummot yangilandi'],200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        $todolist = ToDoList::find($id);
          if(!$todolist){
            return response()->json(['message' => 'Malumot topilmadi!'],404);
          }
        $todolist->delete();
        return response()->json([
            'message' => 'delete',
            'todolist' => $todolist
        ],200);
    }
}
