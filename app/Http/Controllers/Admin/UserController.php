<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __invoke(Request $request)
    {
        $users = User::orderByDesc('created_at')->paginate(20);

        return view('admin.users.index', compact('users'));
    }
}
