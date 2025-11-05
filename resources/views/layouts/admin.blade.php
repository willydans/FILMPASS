<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.head') 
</head>
<body class="bg-gray-100">

    <div class="flex h-screen"> 
        
        @include('partials.admin_sidebar')

        <div class="flex-1 overflow-y-auto"> 
            
            @include('partials.admin_header')

            <main class="bg-gray-100 p-6">
                @yield('content')
            </main>

            @include('partials.admin_footer') 

        </div> </div> </body>
</html>