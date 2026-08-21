<x-layout title="Profile" header="My Profile">
    <div class="max-w-4xl mx-auto">
        {{-- Profile Card --}}
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden mt-10">
            {{-- Header --}}
            <div class="px-6 py-5 bg-gradient from-cyan-50 to-cyan-100/50 border-b border-cyan-100 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">My Profile</h3>
                    <p class="text-sm text-gray-500">Manage your account information</p>
                </div>
                <a href="{{ route('admin.profiles.edit', $user) }}" 
                   class="px-4 py-2 bg-cyan-600 text-white rounded-xl hover:bg-cyan-700 transition text-sm flex items-center gap-2">
                    <i class="fa-regular fa-pen-to-square"></i>
                    Edit Profile
                </a>
            </div>

            {{-- Profile Info --}}
            <div class="p-6">
                <div class="flex items-center gap-6 mb-6">
                    {{-- Avatar --}}
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->first_name . ' ' . $user->last_name) }}&size=96&background=0D9488&color=ffffff&bold=true" 
                    alt="{{ $user->first_name }} {{ $user->last_name }}" 
                    class="w-24 h-24 rounded-full shadow-lg border-4 border-white">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $user->first_name }} {{ $user->last_name }}</h2>
                        <p class="text-gray-500">
                            <i class="fa-regular fa-at mr-1"></i>
                            {{ $user->username }}
                        </p>
                        <p class="text-gray-500 text-sm">
                            <i class="fa-regular fa-envelope mr-1"></i>
                            {{ $user->email }}
                        </p>
                        @if($user->phone)
                            <p class="text-gray-500 text-sm">
                                <i class="fa fa-phone mr-1"></i>
                                {{ $user->phone }}
                            </p>
                        @endif
                    </div>
                </div>

                {{-- Stats --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6 pt-6 border-t border-gray-200">
                    <div class="bg-gray-50 rounded-xl p-4 text-center">
                        <p class="text-2xl font-bold text-cyan-700">{{ $user->created_at->diffForHumans() }}</p>
                        <p class="text-xs text-gray-500">Account Created</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4 text-center">
                        <p class="text-2xl font-bold text-cyan-700">Admin</p>
                        <p class="text-xs text-gray-500">Role</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4 text-center">
                        <p class="text-2xl font-bold text-cyan-700">{{ $user->updated_at->diffForHumans() }}</p>
                        <p class="text-xs text-gray-500">Last Updated</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>