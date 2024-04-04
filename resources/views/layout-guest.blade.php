<!DOCTYPE html>
<html lang="en">

<head>
    <input type="hidden" name="_token" id="csrf-token" value="{{ csrf_token() }}">
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>NDC Coffee</title>
    <link rel="shortcut icon" href="/admin/assets/images/favicon.png" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Josefin+Sans:400,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Great+Vibes" rel="stylesheet">
    <link rel="stylesheet" href="guest/css/open-iconic-bootstrap.min.css">
    <link rel="stylesheet" href="guest/css/animate.css">
    <link rel="stylesheet" href="guest/css/owl.carousel.min.css">
    <link rel="stylesheet" href="guest/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="guest/css/magnific-popup.css">
    <link rel="stylesheet" href="guest/css/aos.css">
    <link rel="stylesheet" href="guest/css/ionicons.min.css">
    <link rel="stylesheet" href="guest/css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="guest/css/jquery.timepicker.css">
    <link rel="stylesheet" href="guest/css/flaticon.css">
    <link rel="stylesheet" href="guest/css/icomoon.css">
    <link rel="stylesheet" href="guest/css/style.css">
    <link rel="stylesheet" href="guest/css/leaflet.css">

    {{-- script --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"
        integrity="sha512-57oZ/vW8ANMjR/KQ6Be9v/+/h6bq9/l3f0Oc7vn6qMqyhvPd1cvKBRWWpzu0QoneImqr2SkmO4MSqU+RpHom3Q=="
        crossorigin="anonymous" referrerpolicy="no-referrer" defer></script>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.19.2/jquery.validate.min.js" defer></script>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.19.2/additional-methods.min.js" defer></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-migrate/3.0.1/jquery-migrate.min.js"
        integrity="sha512-VqTaIU3VlSHylzoMs3hWCBTMZ9l5fvYayp4yzRb5qV9Ne4Z+n21uFoG672gWMcJiedQYZV2KmXF3VkTTsRGRbg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.js"
        integrity="sha512-brrjTp9G6MkKX5QjXwwAQkscfFpKi3okjyZ/M3vGwwg9qI1msoz7z+EbQBLXsTnVK3POjYBGHcQvX34GFKr/7A=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.0/jquery.waypoints.js"
        integrity="sha512-BnrjiUrz5UU3mBQD4awG5ekDBgTPUzpcNJtMQbNj4kfqStjn48180rUTEQjU5+Gf2BBfAtZETnMP4Khv0vcPug=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/stellar.js/0.6.1/jquery.stellar.min.js"
        integrity="sha512-MZiEKWRoqHmTsaur2/0bTQaOT5DqmnMDWuXoYXgNwFzCq+J2rQRIa4uVOmkL7SnIIhis6V4IcPnhqKxgOt8zDg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.1/owl.carousel.min.js"
        integrity="sha512-R5COAyFZ7B88RiuYmY3RPq1uXLFAmRQoGhNF5NU+HaaROvzZ773eLF1guAY8lHM0jNRwgFUVuHWbfPacHXbfDQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"
        integrity="sha512-IsNh5E3eYy3tr/JiX2Yx4vsCujtkhwl7SLqgnwLNgf04Hrt9BT9SXlLlZlWx+OK4ndzAoALhsMNcCmkggjZB1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-animateNumber/0.0.14/jquery.animateNumber.min.js"
        integrity="sha512-WY7Piz2TwYjkLlgxw9DONwf5ixUOBnL3Go+FSdqRxhKlOqx9F+ee/JsablX84YBPLQzUPJsZvV88s8YOJ4S/UA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/scrollax@1.0.0/scrollax.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js"
        integrity="sha512-BwHfrr4c9kmRkLw6iXFdzcdWV/PGkVgiIyIWLLlTSXzWQzxuSg4DiQUCpauz/EWjgk5TYQqX/kvn9pG1NpYfqg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    {{-- <script src="{{ asset('js/bootstrap.min.js') }}"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"
        integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous">
    </script>
    {{-- <script src="{{ asset('js/popper.min.js') }}"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js"
        integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous">
    </script>


</head>

<body>
    @include('guest.navbar')
    @yield('content')
    @include('guest.footer')

    <script src="{{ asset('js/test.js') }}"></script>
    <script src="{{ asset('js/map.js') }}"></script>
    <script src="{{ asset('js/misc1.js') }}"></script>
</body>

</html>