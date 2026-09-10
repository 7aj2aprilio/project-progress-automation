<x-app-layout>
    @php
        $totalUsers = $users->count();
        $adminCount = $users->where('role', 'admin')->count();
        $analisCount = $users->where('role', 'analis')->count();
        $viewerCount = $users->where('role', 'viewer')->count();
    @endphp

    <div x-data="{
        showCreate: false,
        showEdit: false,
        showDelete: false,
        userEdit: {
            id: '', name: '', email: '', role: '', updateUrl: ''
        },
        deleteUrl: '',
        searchQuery: '',
        filterRole: 'all',
        
        openEdit(user, url) {
            this.userEdit.id = user.id;
            this.userEdit.name = user.name;
            this.userEdit.email = user.email;
            this.userEdit.role = user.role;
            this.userEdit.updateUrl = url;
            this.showEdit = true;
        },
        
        openDelete(url) {
            this.deleteUrl = url;
            this.showDelete = true;
        }
    }">
        <x-slot name="header">
            <h2 class="font-bold text-xl text-slate-800 leading-tight">
                {{ __('User Management') }}
            </h2>
        </x-slot>

        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

                {{-- Flash Messages & Errors --}}
                @if(session('success'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl shadow-sm">
                        {{ session('error') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl shadow-sm">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Summary Cards --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Total User -->
                    <div class="bg-sky-50 rounded-2xl p-6 border border-sky-100 shadow-sm flex items-center gap-4">
                        <div class="bg-sky-100 p-3 rounded-xl text-sky-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-sky-800/70">Total Users</p>
                            <div class="flex items-baseline gap-2 mt-1">
                                <h3 class="text-3xl font-bold text-sky-900">{{ $totalUsers }}</h3>
                            </div>
                        </div>
                    </div>
                    <!-- Admin -->
                    <div class="bg-emerald-50 rounded-2xl p-6 border border-emerald-100 shadow-sm flex items-center gap-4">
                        <div class="bg-emerald-100 p-3 rounded-xl text-emerald-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-emerald-800/70">Admin</p>
                            <div class="flex items-baseline gap-2 mt-1">
                                <h3 class="text-3xl font-bold text-emerald-900">{{ $adminCount }}</h3>
                            </div>
                        </div>
                    </div>
                    <!-- Analis -->
                    <div class="bg-blue-50 rounded-2xl p-6 border border-blue-100 shadow-sm flex items-center gap-4">
                        <div class="bg-blue-100 p-3 rounded-xl text-blue-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-blue-800/70">Analyst</p>
                            <div class="flex items-baseline gap-2 mt-1">
                                <h3 class="text-3xl font-bold text-blue-900">{{ $analisCount }}</h3>
                            </div>
                        </div>
                    </div>
                    <!-- Viewer -->
                    <div class="bg-purple-50 rounded-2xl p-6 border border-purple-100 shadow-sm flex items-center gap-4">
                        <div class="bg-purple-100 p-3 rounded-xl text-purple-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-purple-800/70">Viewer</p>
                            <div class="flex items-baseline gap-2 mt-1">
                                <h3 class="text-3xl font-bold text-purple-900">{{ $viewerCount }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Main List Card --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="p-6 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                        <div>
                            <h3 class="text-xl font-bold text-slate-800">User List</h3>
                            <p class="text-sm text-slate-500 mt-1">Manage user data, roles, and system access</p>
                        </div>
                        <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
                            {{-- Search --}}
                            <div class="relative w-full sm:w-64">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </div>
                                <input x-model="searchQuery" type="text" placeholder="Search user..." class="pl-10 pr-4 py-2 w-full border border-slate-200 rounded-xl text-sm focus:ring-primary-500 focus:border-primary-500 shadow-sm transition-all text-slate-700 bg-slate-50 focus:bg-white">
                            </div>
                            
                            {{-- Filter --}}
                            <select x-model="filterRole" class="w-full sm:w-auto border border-slate-200 rounded-xl text-sm py-2 pl-4 pr-10 focus:ring-primary-500 focus:border-primary-500 shadow-sm text-slate-700 bg-slate-50 focus:bg-white cursor-pointer">
                                <option value="all">All Roles</option>
                                <option value="admin">Admin</option>
                                <option value="analis">Analis</option>
                                <option value="viewer">Viewer</option>
                            </select>

                            <button @click="showCreate = true" class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 bg-primary-600 text-white rounded-xl text-sm font-semibold hover:bg-primary-700 focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all shadow-sm whitespace-nowrap">
                                <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Add User
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100">
                            <thead class="bg-slate-50/80">
                                <tr>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider w-16">No</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Role</th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100">
                                @foreach($users as $index => $user)
                                    <tr class="hover:bg-slate-50/50 transition-colors" 
                                        x-show="(filterRole === 'all' || filterRole === '{{ $user->role }}') && (searchQuery === '' || '{{ strtolower($user->name) }}'.includes(searchQuery.toLowerCase()) || '{{ strtolower($user->email) }}'.includes(searchQuery.toLowerCase()))">
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-slate-400 font-medium">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-9 w-9">
                                                    @php
                                                        $initials = collect(explode(' ', $user->name))->map(fn($n) => substr($n, 0, 1))->take(2)->join('');
                                                    @endphp
                                                    <div class="h-9 w-9 rounded-full bg-primary-100 text-primary-700 flex items-center justify-center text-sm font-bold shadow-sm border border-primary-200">
                                                        {{ strtoupper($initials) }}
                                                    </div>
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-semibold text-slate-800">{{ $user->name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-slate-500">{{ $user->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @php
                                                $roleColors = [
                                                    'admin' => 'bg-rose-50 text-rose-600 border-rose-200',
                                                    'analis' => 'bg-sky-50 text-sky-600 border-sky-200',
                                                    'viewer' => 'bg-purple-50 text-purple-600 border-purple-200',
                                                ];
                                            @endphp
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border shadow-sm {{ $roleColors[$user->role] ?? 'bg-slate-50 text-slate-600 border-slate-200' }}">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                            <div class="flex items-center justify-center gap-2">
                                                <button type="button" @click="openEdit({{ json_encode($user) }}, '{{ route('users.update', $user) }}')" class="p-2 bg-amber-50 text-amber-600 hover:bg-amber-100 rounded-lg transition-colors shadow-sm border border-amber-100" title="Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                </button>
                                                @if($user->id !== auth()->id())
                                                    <button type="button" @click="openDelete('{{ route('users.destroy', $user) }}')" class="p-2 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-lg transition-colors shadow-sm border border-rose-100" title="Delete">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                        <div class="p-4 border-t border-slate-100 text-center text-sm text-slate-500" x-show="document.querySelectorAll('tbody tr[style*=\'display: none\']').length === {{ count($users) }}">
                            No user data matches the search or filter.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Create Modal --}}
        <div x-show="showCreate" x-cloak style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showCreate" x-transition.opacity @click="showCreate = false" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="showCreate" x-transition class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100">
                    <form method="POST" action="{{ route('users.store') }}">
                        @csrf
                        <div class="bg-white px-6 pt-6 pb-6">
                            <h3 class="text-xl font-bold text-slate-800 mb-6">Add New User</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Full Name</label>
                                    <input type="text" name="name" required class="w-full rounded-xl border-slate-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 transition-colors bg-slate-50 focus:bg-white text-sm py-2.5">
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email Address</label>
                                    <input type="email" name="email" required class="w-full rounded-xl border-slate-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 transition-colors bg-slate-50 focus:bg-white text-sm py-2.5">
                                </div>

                                <div>
                                    <label for="role" class="block text-sm font-medium text-slate-700 mb-1">System Role</label>
                                    <select name="role" required class="w-full rounded-xl border-slate-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 transition-colors bg-slate-50 focus:bg-white text-sm py-2.5">
                                        <option value="viewer">Viewer</option>
                                        <option value="analis">Analis</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                                    <input type="password" name="password" required class="w-full rounded-xl border-slate-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 transition-colors bg-slate-50 focus:bg-white text-sm py-2.5">
                                </div>

                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Confirm Password</label>
                                    <input type="password" name="password_confirmation" required class="w-full rounded-xl border-slate-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 transition-colors bg-slate-50 focus:bg-white text-sm py-2.5">
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-50/80 px-6 py-4 border-t border-slate-100 flex flex-row-reverse gap-3">
                            <button type="submit" class="inline-flex justify-center rounded-xl border border-transparent px-5 py-2.5 bg-primary-600 text-sm font-semibold text-white hover:bg-primary-700 focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 shadow-sm transition-all">
                                Save User
                            </button>
                            <button type="button" @click="showCreate = false" class="inline-flex justify-center rounded-xl border border-slate-200 px-5 py-2.5 bg-white text-sm font-semibold text-slate-700 hover:bg-slate-50 focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 shadow-sm transition-all">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Edit Modal --}}
        <div x-show="showEdit" x-cloak style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showEdit" x-transition.opacity @click="showEdit = false" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="showEdit" x-transition class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100">
                    <form method="POST" :action="userEdit.updateUrl">
                        @csrf
                        @method('PUT')
                        <div class="bg-white px-6 pt-6 pb-6">
                            <h3 class="text-xl font-bold text-slate-800 mb-6" x-text="'Edit User: ' + userEdit.name"></h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="edit_name" class="block text-sm font-medium text-slate-700 mb-1">Full Name</label>
                                    <input type="text" name="name" id="edit_name" x-model="userEdit.name" required class="w-full rounded-xl border-slate-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 transition-colors bg-slate-50 focus:bg-white text-sm py-2.5">
                                </div>

                                <div>
                                    <label for="edit_email" class="block text-sm font-medium text-slate-700 mb-1">Email Address</label>
                                    <input type="email" name="email" id="edit_email" x-model="userEdit.email" required class="w-full rounded-xl border-slate-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 transition-colors bg-slate-50 focus:bg-white text-sm py-2.5">
                                </div>

                                <div>
                                    <label for="edit_role" class="block text-sm font-medium text-slate-700 mb-1">System Role</label>
                                    <select name="role" id="edit_role" x-model="userEdit.role" required class="w-full rounded-xl border-slate-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 transition-colors bg-slate-50 focus:bg-white text-sm py-2.5">
                                        <option value="viewer">Viewer</option>
                                        <option value="analis">Analis</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="edit_password" class="block text-sm font-medium text-slate-700 mb-1">New Password <span class="font-normal text-slate-400">(Optional)</span></label>
                                    <input type="password" name="password" id="edit_password" class="w-full rounded-xl border-slate-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 transition-colors bg-slate-50 focus:bg-white text-sm py-2.5" placeholder="Leave blank if unchanged">
                                </div>

                                <div>
                                    <label for="edit_password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Confirm New Password</label>
                                    <input type="password" name="password_confirmation" id="edit_password_confirmation" class="w-full rounded-xl border-slate-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 transition-colors bg-slate-50 focus:bg-white text-sm py-2.5">
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-50/80 px-6 py-4 border-t border-slate-100 flex flex-row-reverse gap-3">
                            <button type="submit" class="inline-flex justify-center rounded-xl border border-transparent px-5 py-2.5 bg-primary-600 text-sm font-semibold text-white hover:bg-primary-700 focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 shadow-sm transition-all">
                                Update User
                            </button>
                            <button type="button" @click="showEdit = false" class="inline-flex justify-center rounded-xl border border-slate-200 px-5 py-2.5 bg-white text-sm font-semibold text-slate-700 hover:bg-slate-50 focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 shadow-sm transition-all">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Delete Modal --}}
        <div x-show="showDelete" x-cloak style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showDelete" x-transition.opacity @click="showDelete = false" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="showDelete" x-transition class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100">
                    <form method="POST" :action="deleteUrl">
                        @csrf
                        @method('DELETE')
                        <div class="bg-white px-6 pt-6 pb-6">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-14 w-14 rounded-full bg-rose-100 sm:mx-0 sm:h-12 sm:w-12">
                                    <svg class="h-6 w-6 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                </div>
                                <div class="mt-4 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                    <h3 class="text-xl font-bold text-slate-800" id="modal-title">Delete User Data</h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-slate-500 leading-relaxed">Are you sure you want to permanently delete this user? All related data cannot be recovered once deleted.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-50/80 px-6 py-4 border-t border-slate-100 flex flex-row-reverse gap-3">
                            <button type="submit" class="inline-flex justify-center rounded-xl border border-transparent px-5 py-2.5 bg-rose-600 text-sm font-semibold text-white hover:bg-rose-700 focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 shadow-sm transition-all">
                                Yes, Delete User
                            </button>
                            <button type="button" @click="showDelete = false" class="inline-flex justify-center rounded-xl border border-slate-200 px-5 py-2.5 bg-white text-sm font-semibold text-slate-700 hover:bg-slate-50 focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 shadow-sm transition-all">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
