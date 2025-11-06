<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudioController extends Controller
{
    /**
     * Display a listing of the studios.
     */
    public function index()
    {
        return view('admin.studio'); // studio.blade.php
    }

    /**
     * Show the form for creating a new studio.
     */
    public function create()
    {
        abort(501, 'Not implemented yet.');
    }

    /**
     * Store a newly created studio in storage.
     */
    public function store(Request $request)
    {
        abort(501, 'Not implemented yet.');
    }

    /**
     * Display the specified studio.
     */
    public function show($id)
    {
        abort(501, 'Not implemented yet.');
    }

    /**
     * Show the form for editing the specified studio.
     */
    public function edit($id)
    {
        abort(501, 'Not implemented yet.');
    }

    /**
     * Update the specified studio in storage.
     */
    public function update(Request $request, $id)
    {
        abort(501, 'Not implemented yet.');
    }

    /**
     * Remove the specified studio from storage.
     */
    public function destroy($id)
    {
        abort(501, 'Not implemented yet.');
    }
}
