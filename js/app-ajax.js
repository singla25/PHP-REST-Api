const API_URL = "http://localhost83.local.com/RestApi";

/*
|--------------------------------------------------------------------------
| Render Students
|--------------------------------------------------------------------------
*/

function renderStudents(data){

    let output = '';

    if(data.status){

        $.each(data.data, function(index, student){

            output += `
                <tr>
                    <td>${student.id}</td>
                    <td>${student.name}</td>
                    <td>${student.email}</td>
                    <td>${student.gender}</td>
                    <td>${student.date_of_birth}</td>
                    <td>${student.salary}</td>
                    <td>
                        <button class="action-btn edit" onclick="editStudent(${student.id})">
                            Edit
                        </button>

                        <button class="action-btn delete" onclick="deleteStudent(${student.id})">
                            Delete
                        </button>
                    </td>
                </tr>
            `;
        });
    } else {
        output = `
            <tr>
                <td colspan="7">No Record Found</td>
            </tr>
        `;
    }

    $('#studentTable').html(output);
}

/*
|--------------------------------------------------------------------------
| Load Students
|--------------------------------------------------------------------------
*/

function loadStudents(){

    $.ajax({
        url: `${API_URL}/api-fetch-all.php`,
        type: 'GET',
        success: function(response){
            renderStudents(response);
        }
    });
}

/*
|--------------------------------------------------------------------------
| Live Search
|--------------------------------------------------------------------------
*/

$('#search').on('keyup', function(){

    let search = $(this).val();

    if(search === ''){
        loadStudents();
        return;
    }

    $.ajax({
        url: `${API_URL}/api-search.php?s=${search}`,
        type: 'GET',
        success: function(response){
            renderStudents(response);
        }
    });
});

/*
|--------------------------------------------------------------------------
| Insert / Update Student
|--------------------------------------------------------------------------
*/

$('#studentForm').on('submit', function(e){

    e.preventDefault();

    let studentId = $('#student_id').val();

    let studentData = {
        sid: studentId,
        sname: $('#name').val(),
        semail: $('#email').val(),
        sgender: $('#gender').val(),
        sdob: $('#dob').val(),
        ssalary: $('#salary').val()
    };

    let apiFile = studentId ? 'api-update.php' : 'api-insert.php';
    let method = studentId ? 'PUT' : 'POST';

    $.ajax({
        url: `${API_URL}/${apiFile}`,
        type: method,
        contentType: 'application/json',
        data: JSON.stringify(studentData),
        success: function(response){

            showToast(response.message);
            $('#studentForm')[0].reset();
            $('#student_id').val('');

            /*
            |--------------------------------------------------------------------------
            | Reset Button
            |--------------------------------------------------------------------------
            */

            $('#submitBtn')
                .text('Save Student')
                .css('background', '#2563eb');

            loadStudents();
        }
    });
});

/*
|--------------------------------------------------------------------------
| Edit Student
|--------------------------------------------------------------------------
*/

function editStudent(id){

    $.ajax({

        url: `${API_URL}/api-fetch-single.php`,

        type: 'POST',

        contentType: 'application/json',

        data: JSON.stringify({
            sid:id
        }),

        success: function(response){
            let student = response.data;
            $('#student_id').val(student.id);
            $('#name').val(student.name);
            $('#email').val(student.email);
            $('#gender').val(student.gender);
            $('#dob').val(student.date_of_birth);
            $('#salary').val(student.salary);

            /*
            |--------------------------------------------------------------------------
            | Update Button
            |--------------------------------------------------------------------------
            */

            $('#submitBtn')
                .text('Update Student')
                .css('background', '#f59e0b');

            window.scrollTo({
                top:0,
                behavior:'smooth'
            });
        }
    });
}

/*
|--------------------------------------------------------------------------
| Delete Modal
|--------------------------------------------------------------------------
*/

let deleteStudentId = null;

function deleteStudent(id){
    deleteStudentId = id;
    $('#deleteModal').addClass('show');
}

function closeModal(){
    $('#deleteModal').removeClass('show');
}

/*
|--------------------------------------------------------------------------
| Confirm Delete
|--------------------------------------------------------------------------
*/

$('#confirmDeleteBtn').on('click', function(){

    $.ajax({

        url: `${API_URL}/api-delete.php`,

        type: 'DELETE',

        contentType: 'application/json',

        data: JSON.stringify({
            sid: deleteStudentId
        }),

        success: function(response){
            showToast(response.message);
            closeModal();
            loadStudents();
        }
    });
});

/*
|--------------------------------------------------------------------------
| Toast Notification
|--------------------------------------------------------------------------
*/

function showToast(message){

    $('#toast')
        .text(message)
        .addClass('show');

    setTimeout(function(){
        $('#toast').removeClass('show');
    }, 3000);
}

/*
|--------------------------------------------------------------------------
| Initial Load
|--------------------------------------------------------------------------
*/

loadStudents();