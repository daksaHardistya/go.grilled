<x-layoute-admin>

    <head>
        <title>Login Admin - Go.Grilled</title>
    </head>

    <body class="bg-gradient-to-br from-gray-100 to-gray-300 font-sans min-h-screen flex items-center justify-center">
        <div class="bg-white shadow-lg rounded-2xl w-full max-w-sm p-8 text-center">
            <div class="flex justify-center mb-6">
                <img src="{{ asset('img/logos/logo.png') }}" alt="Logo Go.Grilled" class="w-24 h-auto">
            </div>
            <h3 class="text-xl font-bold text-gray-700 mb-4">Login Admin</h3>
            <form method="POST" action="{{ route('admin.login') }}" class="space-y-4">
                @csrf

                @error('loginError')
                    <div class="text-red-600 text-sm">{{ $message }}</div>
                @enderror

                <input type="text" name="username" placeholder="Username" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-400">

                <input type="password" name="password" placeholder="Password" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-400">

                <button type="submit"
                    class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-2 rounded-md transition duration-200">
                    Login
                </button>
            </form>
        </div>
    </body>
</x-layoute-admin>
