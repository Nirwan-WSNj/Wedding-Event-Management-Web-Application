@extends('layouts.app')
@section('title', 'Admin Users Management')
@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">User Management</h1>
    <form method="GET" action="" class="mb-4 flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, user code, role..." class="border rounded px-3 py-2">
        <select name="role" class="border rounded px-3 py-2">
            <option value="">All Roles</option>
            <option value="admin" @if(request('role')=='admin') selected @endif>Admin</option>
            <option value="manager" @if(request('role')=='manager') selected @endif>Manager</option>
            <option value="customer" @if(request('role')=='customer') selected @endif>Customer</option>
        </select>
        <button class="bg-blue-600 text-white px-4 py-2 rounded">Filter</button>
    </form>
    <table class="min-w-full bg-white border rounded shadow">
        <thead>
            <tr>
                <th class="px-4 py-2">User Code</th>
                <th class="px-4 py-2">Name</th>
                <th class="px-4 py-2">Email</th>
                <th class="px-4 py-2">Role</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td class="px-4 py-2 font-mono">{{ $user->user_code }}</td>
                <td class="px-4 py-2">{{ $user->first_name }} {{ $user->last_name }}</td>
                <td class="px-4 py-2">{{ $user->email }}</td>
                <td class="px-4 py-2">{{ ucfirst($user->role) }}</td>
                <td class="px-4 py-2">
                    <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-600 hover:underline">Edit</a>
                    <form action="{{ route('admin.users.delete', $user) }}" method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline ml-2" onclick="return confirm('Delete this user?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">{{ $users->links() }}</div>
    <h2 class="text-xl font-semibold mt-8 mb-2">Add New User</h2>
    <form method="POST" action="{{ route('admin.users.create') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @csrf
        <input type="text" name="first_name" placeholder="First Name" class="border rounded px-3 py-2" required>
        <input type="text" name="last_name" placeholder="Last Name" class="border rounded px-3 py-2" required>
        <input type="email" name="email" placeholder="Email" class="border rounded px-3 py-2" required>
        <select name="role" class="border rounded px-3 py-2" required>
            <option value="">Select Role</option>
            <option value="admin">Admin</option>
            <option value="manager">Manager</option>
            <option value="customer">Customer</option>
        </select>
        <input type="password" name="password" placeholder="Password" class="border rounded px-3 py-2" required>
        <button class="bg-green-600 text-white px-4 py-2 rounded col-span-1 md:col-span-2">Create User</button>
    </form>
</div>
@endsection
