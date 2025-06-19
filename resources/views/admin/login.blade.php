<x-layoute-admin>
    <head>
        <title>Login Admin</title>
        <style>
            body { background: #f2f2f2; font-family: Arial; }
            .login-box {
                margin-top: 100px;
                padding: 30px;
                background: white;
                width: 300px;
                margin-left: auto;
                margin-right: auto;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
                border-radius: 10px;
            }
            input, button {
                width: 100%;
                margin: 10px 0;
                padding: 10px;
            }
            .tombol-login {
                background: #3498db;
                color: white;
                border: none;
                cursor: pointer;
            }
            .error { color: red; font-size: 0.9em; }
        </style>
    </head>
    <body>
        <div class="login-box">
            <h3>Login Admin</h3>
            <form method="POST" action="{{ route('admin.login') }}">
                @csrf
                @error('loginError')
                    <div class="error">{{ $message }}</div>
                @enderror
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" class="tombol-login">Login</button>
            </form>
        </div>
    </body>    
</x-layoute-admin>