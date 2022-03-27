<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Hello, world!</title>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="card mt-5">
                <div class="card-header">
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambah">
                        Tambah
                    </button>
                </div>
                <div class="card-body">
                    <div class="col-12">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">First</th>
                                    <th scope="col">Last</th>
                                    <th scope="col">Handle</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="tambah" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="" id="saveform_error"></div>
                    <div class="form-group mb-3">
                        <label for="">Email </label>
                        <input type="email" class="form-control email" name="email" id="" aria-describedby="emailHelp"
                            placeholder="Enter email">
                        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone
                            else.</small>
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Name </label>
                        <input type="text" class="form-control name" name="name" id="" aria-describedby="emailHelp"
                            placeholder="Enter name">
                        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone
                            else.</small>
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Password </label>
                        <input type="email" class="form-control password" name="password" id=""
                            aria-describedby="emailHelp" placeholder="Enter password">
                        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone
                            else.</small>
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Konfrimasi Password </label>
                        <input type="email" class="form-control password_confirmation" name="password_confirmation"
                            id="" aria-describedby="emailHelp" placeholder="Enter email">
                        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone
                            else.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary add_user">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Optional JavaScript; choose one of the two! -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
            
            fetchdata();
            function fetchdata(){
                $.ajax({
                    url: "{{ route('data') }}",
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        $('tbody').html("");
                       $.each(data.data, function (key, item) { 
                           $('tbody').append('<tr>\
                            <td>' + item.id + '</td>\
                            <td>' + item.name + '</td>\
                            <td>' + item.email + '</td>\
                            <td><button type="button" value="' + item.id + '" class="btn btn-primary editbtn btn-sm">Edit</button>\
                           <button type="button" value="' + item.id + '" class="btn btn-danger deletebtn btn-sm">Delete</button></td>\
                            \
                        </tr>');
                       });
                    }
                });
            }

            $(document).on("click",'.editbtn',function (e) {
                e.preventDefault();
                var id = $(this).val();
                console.log(id);
                $.ajax({
                    url: "/user/" + id,
                    type: "GET",
                    dataType: "json",
                    data: {
                        id: id
                    },
                    success: function (data) {
                        $('.name').val(data.data.name);
                        $('.email').val(data.data.email);
                        $('.password').val(data.data.password);
                        $('.add_user').attr('id',data.data.id);
                        $('#tambah').modal('show');
                    }
                });
            });
 
            $(document).on("click",'.add_user',function (e) {
                e.preventDefault();
                var data = {
                    'email': $('.email').val(),
                    'name': $('.name').val(),
                    'password': $('.password').val(),
                    'password_confirmation': $('.password_confirmation').val()
                }
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: "{{ route('user.store') }}",
                    data: data,
                    dataType: "json",
                    success: function (response) {
                        // console.log(response);
                        if (response.status == 400) {
                           $.each(response.errors, function (key, err_value) {
                                $('#saveform_error').append('<div class="alert alert-danger">' + err_value + '</div>');
                           });
                           Swal.fire({
                                title: 'error',
                                text: response.message,
                                icon: 'error',
                                confirmButtonText: 'Ok'
                          })
                        } else {
                            console.log(response.status);
                            Swal.fire({
                                title: 'success',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'Ok'
                            })
                            $('#tambah').modal('hide');
                            $('#tambah').find('input').val('');
                            fetchdata();
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>