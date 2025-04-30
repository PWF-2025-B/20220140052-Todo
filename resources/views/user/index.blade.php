<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                
                <!-- Search Form -->
                <div class="p-6 text-gray-900 dark:text-100">
                    <form method="GET" action="{{ route('user.index') }}" class="mb-4">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}" 
                            class="px-4 py-2 border border-gray-300 rounded-md" 
                            placeholder="Search by name or email" 
                            autofocus
                        />
                        <button type="submit" class="ml-2 px-4 py-2 bg-blue-500 text-white rounded-md">
                            Search
                        </button>
                    </form>

                    <!-- Flash Messages -->
                    @if (session('success'))
                        <p x-data="{ show: true }" x-show="show" x-transition
                           x-init="setTimeout(() => show = false, 5000)"
                           class="text-sm text-green-600 dark:text-green-400">
                            {{ session('success') }}
                        </p>
                    @endif

                    @if (session('danger'))
                        <p x-data="{ show: true }" x-show="show" x-transition
                           x-init="setTimeout(() => show = false, 5000)"
                           class="text-sm text-red-600 dark:text-red-400">
                            {{ session('danger') }}
                        </p>
                    @endif
                </div>

                <!-- User Table -->
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Id</th>
                                <th scope="col" class="px-6 py-3">Nama</th>
                                <th scope="col" class="hidden px-6 py-3 md:block">Email</th>
                                <th scope="col" class="px-6 py-3">Todo</th>
                                <th scope="col" class="px-6 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr class="odd:bg-white odd:dark:bg-gray-800 even:bg-gray-50 even:dark:bg-gray-700">
                                    <td class="px-6 py-4 font-medium whitespace-nowrap dark:text-white">{{ $user->id }}</td>
                                    <td class="px-6 py-4">{{ $user->name }}</td>
                                    <td class="hidden px-6 py-4 md:block">{{ $user->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <p>
                                            {{ $user->todos->count() }}
                                            <span>
                                                <span class="text-green-600 dark:text-green-400">
                                                    ({{ $user->todos->where('is_done', true)->count() }})
                                                </span> / 
                                                <span class="text-blue-600 dark:text-blue-400">
                                                    {{ $user->todos->where('is_done', false)->count() }}
                                                </span>
                                            </span>
                                        </p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex space-x-3">
                                            <!-- Admin Access -->
                                            @if ($user->is_admin)
                                                <form action="{{ route('user.removeadmin', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove admin access?')">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="text-blue-600 dark:text-blue-400 whitespace-nowrap">
                                                        Remove Admin
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('user.makeadmin', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to make this user an admin?')">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="text-red-600 dark:text-red-400 whitespace-nowrap">
                                                        Make Admin
                                                    </button>
                                                </form>
                                            @endif

                                            <!-- Delete Button -->
                                            <form action="{{ route('user.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 dark:text-red-400 whitespace-nowrap">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr class="odd:bg-white odd:dark:bg-gray-800 even:bg-gray-50 even:dark:bg-gray-700">
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        No data available
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-5">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>