@extends('layouts.mainMaster')

@section('main_content')
        
    <div class="wrapper theme-1-active pimary-color-red">
            
        @include('layouts.partials.header')

        @include('layouts.partials.sidebarLeft')

        @include('layouts.partials.sidebarRight')

        @include('layouts.partials.settingsRight')

        @include('layouts.partials.sidebarBackdropRight')

        <div class="page-wrapper">

            @yield('content')

            <!-- Footer -->
            @include('layouts.partials.footer')
            <!-- /Footer -->

        </div>

    </div>

@endsection
