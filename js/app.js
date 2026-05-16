// js/app.js

const API_URL = "http://localhost83.local.com/RestApi";

/*
|--------------------------------------------------------------------------
| Render Students
|--------------------------------------------------------------------------
*/

function renderStudents(data){

    let output = '';

    if(data.status){

        data.data.forEach(student => {

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

    document.getElementById('studentTable').innerHTML = output;
}

/*
|--------------------------------------------------------------------------
| Load Students
|--------------------------------------------------------------------------
*/

function loadStudents(){

    fetch(`${API_URL}/api-fetch-all.php`)
    .then(response => response.json())
    .then(data => {
        renderStudents(data);
    });
}

/*
|--------------------------------------------------------------------------
| Live Search
|--------------------------------------------------------------------------
*/

document
.getElementById('search')
.addEventListener('keyup', function(){

    let search = this.value;

    if(search === ''){
        loadStudents();
        return;
    }

    fetch(`${API_URL}/api-search.php?s=${search}`)
    .then(response => response.json())
    .then(data => {
        renderStudents(data);
    });
});

/*
|--------------------------------------------------------------------------
| Insert / Update Student
|--------------------------------------------------------------------------
*/

document
.getElementById('studentForm')
.addEventListener('submit', function(e){

    e.preventDefault();

    let studentId = document.getElementById('student_id').value;

    let studentData = {
        sid: studentId,
        sname: document.getElementById('name').value,
        semail: document.getElementById('email').value,
        sgender: document.getElementById('gender').value,
        sdob: document.getElementById('dob').value,
        ssalary: document.getElementById('salary').value
    };

    let apiFile = studentId ? 'api-update.php' : 'api-insert.php';
    let method = studentId ? 'PUT' : 'POST';

    fetch(`${API_URL}/${apiFile}`, {

        method: method,

        headers:{
            'Content-Type':'application/json'
        },

        body: JSON.stringify(studentData)

    })

    .then(response => response.json())

    .then(data => {

        alert(data.message);

        document.getElementById('studentForm').reset();
        document.getElementById('student_id').value = '';

        loadStudents();
    });
});

/*
|--------------------------------------------------------------------------
| Edit Student
|--------------------------------------------------------------------------
*/

function editStudent(id){

    fetch(`${API_URL}/api-fetch-single.php`, {

        method:'POST',

        headers:{
            'Content-Type':'application/json'
        },

        body: JSON.stringify({
            sid:id
        })

    })

    .then(response => response.json())

    .then(data => {

        let student = data.data;

        document.getElementById('student_id').value = student.id;
        document.getElementById('name').value = student.name;
        document.getElementById('email').value = student.email;
        document.getElementById('gender').value = student.gender;
        document.getElementById('dob').value = student.date_of_birth;
        document.getElementById('salary').value = student.salary;

        window.scrollTo({
            top:0,
            behavior:'smooth'
        });
    });
}

/*
|--------------------------------------------------------------------------
| Delete Student
|--------------------------------------------------------------------------
*/

function deleteStudent(id){

    if(confirm("Delete this student?")){

        fetch(`${API_URL}/api-delete.php`, {

            method:'DELETE',

            headers:{
                'Content-Type':'application/json'
            },

            body: JSON.stringify({
                sid:id
            })
        })

        .then(response => response.json())

        .then(data => {
            alert(data.message);
            loadStudents();
        });
    }
}

/*
|--------------------------------------------------------------------------
| Initial Load
|--------------------------------------------------------------------------
*/

loadStudents();