<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::orderBy('id', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name->en', 'like', "%{$search}%")
                  ->orWhere('name->gu', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $limit = $request->input('limit', 10);
        $users = $query->paginate($limit)->withQueryString();

        if ($request->ajax()) {
            return view('admin.user.view', compact('users'));
        }

        return view('admin.user.index', compact('users'));
    }

    public function create()
    {
        return view('admin.user.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|in:super_admin,admin',
            'webpage_url' => 'nullable|url'
        ]);

        $enName = $request->name;
        $guName = $request->name_gu;

        if (empty($guName) && !empty($enName)) {
            try {
                $guName = \Stichoza\GoogleTranslate\GoogleTranslate::trans($enName, 'gu', 'en');
            } catch (\Exception $e) {
                $guName = $enName;
            }
        }

        User::create([
            'name' => ['en' => $enName, 'gu' => $guName],
            'email' => $request->email,
            'password' => Hash::make('12345678'), // Default password
            'role' => $request->role,
            'is_admin' => 1,
            'webpage_url' => $request->webpage_url,
        ]);

        return redirect()->route('admin.user.index')->with('success', 'User created successfully with default password 12345678.');
    }

    public function show($id)
    {
        return redirect()->route('admin.user.edit', $id);
    }

    public function edit(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $page = $request->page;
        return view('admin.user.edit', compact('user', 'page'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$id,
            'role' => 'required|in:super_admin,admin',
            'webpage_url' => 'nullable|url'
        ]);

        $enName = $request->name;
        $guName = $request->name_gu;

        if (empty($guName) && !empty($enName)) {
            try {
                $guName = \Stichoza\GoogleTranslate\GoogleTranslate::trans($enName, 'gu', 'en');
            } catch (\Exception $e) {
                $guName = $enName;
            }
        }

        $user->name = ['en' => $enName, 'gu' => $guName];
        $user->email = $request->email;
        $user->role = $request->role;
        $user->webpage_url = $request->webpage_url;

        $user->save();

        return redirect()->route('admin.user.index', ['page' => $request->page])->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.user.index')->with('error', 'You cannot delete yourself.');
        }
        $user->delete();
        return redirect()->route('admin.user.index')->with('success', 'User deleted successfully.');
    }
}
