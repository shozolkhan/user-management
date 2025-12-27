<!DOCTYPE html>
<html>
<head>
    <title>User Management</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <style>
        #editModal {
            display: none;
            position: fixed;
            top: 30%;
            left: 40%;
            background: #fff;
            padding: 20px;
            border: 1px solid #000;
        }
    </style>
</head>

<body>

<h2>User List</h2>

<!-- EXPORT -->
<form>
    From:
    <input type="date" id="from_date">
    To:
    <input type="date" id="to_date">
    <button type="button" id="excel">Excel</button>
    <button type="button" id="pdf">PDF</button>
</form>

<hr>

<!-- TABLE -->
<table id="users-table" class="display" style="width:100%">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Created</th>
            <th>Action</th>
        </tr>
    </thead>
</table>

<!-- EDIT MODAL -->
<div id="editModal">
    <h3>Edit User</h3>
    <input type="hidden" id="user_id">

    <div>
        <label>Name</label><br>
        <input type="text" id="name">
    </div>

    <div>
        <label>Email</label><br>
        <input type="email" id="email">
    </div>

    <br>
    <button id="updateUser">Update</button>
    <button onclick="$('#editModal').hide()">Cancel</button>
</div>

<script>
/* CSRF */
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

let table;

$(document).ready(function () {

    table = $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('users.data') }}",
        columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'email' },
            { data: 'created_at' },
            { data: 'action', orderable: false, searchable: false }
        ]
    });

});

/* EXPORT */
$('#excel').click(function () {
    let f = $('#from_date').val();
    let t = $('#to_date').val();
    window.location = `/users/export/excel?from_date=${f}&to_date=${t}`;
});

$('#pdf').click(function () {
    let f = $('#from_date').val();
    let t = $('#to_date').val();
    window.location = `/users/export/pdf?from_date=${f}&to_date=${t}`;
});

/* DELETE */
function deleteUser(id) {
    if (!confirm('Are you sure?')) return;

    $.ajax({
        url: '/users/' + id,
        type: 'DELETE',
        success: function () {
            table.ajax.reload(null, false);
        }
    });
}

/* EDIT BUTTON CLICK */
$(document).on('click', '.editBtn', function () {

    let id    = $(this).data('id');
    let name  = $(this).data('name');
    let email = $(this).data('email');

    $('#user_id').val(id);
    $('#name').val(name);
    $('#email').val(email);

    $('#editModal').show();
});


/* UPDATE */
$('#updateUser').click(function () {
    let id = $('#user_id').val();

    $.ajax({
        url: '/users/' + id,
        type: 'PUT',
        data: {
            name: $('#name').val(),
            email: $('#email').val()
        },
        success: function () {
            $('#editModal').hide();
            table.ajax.reload(null, false);
        }
    });
});
</script>

</body>
</html>
