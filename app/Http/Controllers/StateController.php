<?php

namespace App\Http\Controllers;

use App\Models\State;
use http\Env\Response;
use Illuminate\Http\Request;

class StateController extends Controller
{
    //
    public function getAll() {
        $states = State::where("name","Hawaii")->get();
        return response()->json([
            "message" => "States Fetched Successfully",
            "data" => $states
        ]);
    }
    public function getAll2() {
        $states = State::all();
        return response()->json([
            "message" => "States Fetched Successfully",
            "data" => $states
        ]);
    }

    public function getById($id) {
        $state = State::find($id);
        if (!empty($state)) {
            return response()->json([
               "message" => "State Fetch Successful",
               "data" => $state
            ]);
        } else {
            return response()->json([
                "message" => "State not Found"
            ], 404);
        }
    }

    public function create(Request $request){
        $state = new State();
        $state->name = $request->name;
        $state->abbreviation = $request->abbreviation;
        $state->save();

        return response()->json([
            "message" => "State Created",
            "data" => $state
        ], 201);
    }
    public function update(Request $request, $id){
        if (State::where('id', $id)->exists()) {
            $state = State::findorfail($id);
            $state -> name = is_null($request->name) ? $state->name : $request->name;
            $state -> abbreviation = is_null($request->abbreviation) ? $state->abbreviation : $request->abbreviation;

            $state->save();
        }
        return response()->json([
            "message" => "update successful",
            "data" => $state
        ]);
    }

    public function destroy($id)
    {
        if(State::where('id', $id)->exists()){
            $state = State::find($id);
            $state->delete();


            return response()->json([
                "message" => "State Deleted"
            ], 202);
        } else {
            return response()->json([
                "message" => "State Not Found"
            ], 404);
        }
    }
}
