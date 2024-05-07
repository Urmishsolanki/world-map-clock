<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title></title>
<meta content="" name="description">
<meta content="" name="keywords">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="{{ URL::asset('public/img/favicon.png')}}" rel="icon">
<!-- Google Fonts -->
<link href="https://fonts.gstatic.com" rel="preconnect">
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
<!-- Vendor CSS Files -->
<link href="{{ URL::asset('public/backend/assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
<link href="{{ URL::asset('public/backend/assets/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
<!-- Template Main CSS File -->
<link href="{{ URL::asset('public/backend/assets/css/style.css')}}" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<!-- Custome Style -->
<style type="text/css">
    .header .toggle-sidebar-btn {
        padding-left: 0px;
    }
    .logo span {
        font-size: 23px;
    }
    .logo img {
        max-height: 40px;
        margin-right: 6px;
    }
    .header-nav .nav-profile img {
        max-height: 25px;
    }
    .pac-container {
        z-index: 99999;
    }
    .modelHeading {
        border-radius: 10px;
        width: 100%;
        background-color: #d4edda;
    }
    #modelHeading {

        margin-left: 20px;
        color: #155724;
        border-color: #c3e6cb
    }
</style>