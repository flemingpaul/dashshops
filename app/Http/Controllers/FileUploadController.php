<?php

namespace App\Http\Controllers;

use App\Models\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FileUploadController extends Controller
{
    //
    public function index()
    {

        $files = FileUpload::all();

        return view('pages.files', compact('files'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('files.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $image = $request->file('file');
        $FileName = $image->getClientOriginalName();
        $image->move(public_path('images'), $FileName);

        $imageUpload = new FileUpload();
        $imageUpload->filename = $FileName;
        $imageUpload->save();
        return response()->json(['success' => $FileName]);
    }

    /**
     * Display the specified resource.
     *
     * @param FileUpload $fileUpload
     * @return Response
     */
    public function show(FileUpload $fileUpload)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param FileUpload $fileUpload
     * @return Response
     */
    public function edit(FileUpload $fileUpload)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param FileUpload $fileUpload
     * @return Response
     */
    public function update(Request $request, FileUpload $fileUpload)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param FileUpload $fileUpload
     * @return Response
     */
    public function destroy($id)
    {

        $fileUpload = FileUpload::find($id);

        $fileUpload->delete();

        return redirect()->back()
            ->with('success', 'File deleted successfully');
    }

}
