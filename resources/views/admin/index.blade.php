<!DOCTYPE html>
<html lang="en">
@include('admin.common.head')

<body class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->
        @include('admin.common.menu')
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
            @include('admin.common.nav')

            <div class="content-wrapper">
                @yield('content')
            </div>
        </div>
        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>

        {{-- @include('admin.common.footer') --}}
        @include('admin.common.script')

        @stack('scripts')

    </div>
</body>

</html>
