<nav class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-50">
    <div class="max-w-full mx-auto px-4 lg:px-6">
        <div class="flex items-center justify-between h-16">

            {{-- LEFT: hamburger + optional page title / breadcrumb --}}
            <div class="flex items-center gap-4">
                {{-- hamburger --}}
                <button id="admin-hamburger" class="p-2 rounded-md hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-green-300">
                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>

            {{-- RIGHT: icons + user dropdown --}}
            <div class="flex items-center gap-4">
                {{-- Notification bell --}}
                <button class="p-2 rounded-md hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-green-300">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 11-6 0h6z"/>
                    </svg>
                </button>

                {{-- vertical divider --}}
                <div class="hidden sm:block h-8 border-l border-gray-200"></div>

                    {{-- User dropdown (Preline structure hs-dropdown) --}}
                    <div class="hs-dropdown relative inline-flex">
                        <button id="hs-dropdown-user-admin" type="button" class="hs-dropdown-toggle inline-flex items-center gap-3 bg-white border border-gray-100 shadow-sm rounded-full px-3 py-2 hover:shadow-md focus:outline-none" aria-expanded="false" aria-label="Open user menu">

                            {{-- avatar --}}
                            <img src="/mnt/data/6ebd7017-921f-43fb-8712-49b42286f311.png" alt="{{ Auth::user()->nama_lengkap ?? 'User' }}" class="w-9 h-9 rounded-full object-cover border-2 border-white shadow-sm">

                            {{-- name + role --}}
                            <div class="hidden sm:flex flex-col items-start leading-tight text-left">
                                <span class="text-sm font-medium text-gray-800 truncate">{{ Auth::user()->nama_lengkap ?? 'Nama User' }}</span>
                                <span class="text-xs text-green-600">Pemilik</span>
                            </div>

                            {{-- caret --}}
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        {{-- dropdown menu (Preline template) --}}
                        <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 w-56 hidden z-10 mt-2 min-w-60 bg-white rounded-lg shadow-lg border border-gray-100" role="menu" aria-labelledby="hs-dropdown-user-admin">
                            <div class="py-2">
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 text-gray-700">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A6 6 0 0112 15a6 6 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span>Profil Saya</span>
                                </a>


                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left flex items-center gap-3 px-4 py-3 hover:bg-gray-50 text-red-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7"/>
                                        </svg>
                                        <span>Logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
        </div>
    </div>
</nav>
