<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DataPenyewaController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);

        return view('admin.data-penyewa.index', compact('users'));
    }
}
