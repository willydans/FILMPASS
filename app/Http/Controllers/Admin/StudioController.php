<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudioController extends Controller
{
    public function index()
    {
        // Cek apakah file ada di resources/views/admin/studio.blade.php
        return view('admin.studio');
    }

    // Method lain kosong dulu, supaya gak bikin error baru
    public function create() {}
    public function store(Request $request) {}
    public function show(string $id) {}
    public function edit(string $id) {}
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}
}
