<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
<form method="POST" action="{{session()->get('phone') ? route('lwo') : route('make-otp')}}">
    @csrf
    <div>
        @if($errors->any())
            <div class="bg-red-700">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{$error}}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
    <div class="mt-4">
        <div class="mt-4">
            <label class="block" for="phone">phone<label>
                    <input type="text" placeholder="phone" name="phone"
{{--                           class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600" value="{{isset($phone)}}">--}}
                           class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600" value="{{session()->get('phone')??''}}">
        </div>
        <div class="mt-4">
            <label class="block" for="phone">code<label>
                    <input type="text" placeholder="phone" name="code"
                           class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600">
        </div>
        {{--        <div class="mt-4">--}}
        {{--            <label class="block">Password<label>--}}
        {{--                    <input type="password" placeholder="Password" name="password"--}}
        {{--                           class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600">--}}
        {{--        </div>--}}

        <span class="text-xs text-red-400">Password must be same!</span>
        <div class="flex">
            <button class="w-full px-6 py-2 mt-4 text-white bg-blue-600 rounded-lg hover:bg-blue-900">Create
                Account
            </button>
        </div>
        <div class="mt-6 text-grey-dark">
            Already have an account?
            <a class="text-blue-600 hover:underline" href="#">
                Log in
            </a>
        </div>
    </div>
</form>

</body>
</html>
