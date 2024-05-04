<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="new web site">
    <meta name="author" content="saravanan">
    <meta name="keyword" content="html,Dashboard">
    <title>Finscale</title>
    <link rel="icon" type="image/png" sizes="16x16" href="assets/favicon/favicon-16x16.png">

    <!-- Main styles for this application-->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}" >
    <link rel="stylesheet" type="text/css" href="{{ asset('css/finscale.css') }}" >
    <link rel="stylesheet" type="text/css" href="{{ asset('css/tabs.css') }}" >
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.1.2/css/fontawesome.min.css" integrity="sha384-X8QTME3FCg1DLb58++lPvsjbQoCT9bp3MsUU3grbIny/3ZwUJkRNO8NPW6zqzuW9" crossorigin="anonymous"> -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
	
    <style>
        .page-item .page-link{
            color: gray !important;
        }

        .page-item.active .page-link {
            z-index: 1;
            color: #fff !important;
            background-color: green; //your color
        }
    </style>
</head>

<body>
    <div class="sidebar sidebar-dark sidebar-fixed" id="sidebar">
         @include('layouts.sidebar')
    </div>
    <div class="wrapper d-flex flex-column min-vh-100 bg-light">
        <header class="header header-sticky p-3 mb-3 header-text border-bottom">
            <div class="container-fluid d-black d-sm-none">
                <div class="header-toggler px-md-0 me-md-3" type="button"
                    onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                </div>
                <!-- <a class="header-brand d-md-none" href="#">
      X</a> -->
            </div>
            
            <div class="d-flex justify-content-between bd-highlight mb-6 align-items-center">
                @yield('header')
                <!-- <div class="delect-btn"><button class="btns border-0 red-text"><i class="fa fa-trash" aria-hidden="true"></i><span class="pl-2">Delete</span></button></div> -->
            </div>
        </header>


        @yield('search-section')
        
        <div class="body flex-grow-1 px-3">
            @yield('content')
        </div>
        <!-- <footer class="footer">
      <div><a href="#">new site</a> © 2022 creativeLabs.</div>
    </footer> -->
    </div>
    <!-- CoreUI and necessary plugins-->
    <script src="{{ asset('vendors/@coreui/coreui/js/coreui.bundle.min.js') }}"></script>
    <script src="{{ asset('vendors/simplebar/js/simplebar.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>

    {{-- toastr js --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
    {{-- sweet alert js --}}
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    {{-- chart js --}}
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	{{-- Date Range Picker --}}
	<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
	{{-- Table to Excel--}}
	<script src="https://cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
	<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
	{{-- Select 2--}}
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
	{{-- pdf --}}
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.min.js"></script>

    <script>
        $(document).ready(function() {
            toastr.options.timeOut = 10000;
            @if (Session::has('error'))
                toastr.error('{{ Session::get('error') }}');
            @elseif(Session::has('success'))
                toastr.success('{{ Session::get('success') }}');
            @endif
        });
		
		$('#page_size').on('change', function() {
			var url = '{{ url('/set-pagination/') }}/'+this.value.toString();
			window.location.href = url;
		});

    </script>
    @yield('scripts')
</body>

</html>