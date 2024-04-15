const {showSpinner, hideSpinner} = require("../general");

const getStudents = function (query = '') {
    showSpinner();

    $.ajax({
        url: '/api/university/' + universityId +'/students?' + query,
        method: 'GET',
        success: function (response) {
            displayStudentsData(response.data);
            hideSpinner();
        },
        error: function (xhr, status, error) {
            console.error('Помилка:', error);
            hideSpinner();
        }
    });
}

const searchStudents = function () {
    let query = '';

    const surnameInput = $('.search-tool input[name="surname"]').val();
    if (surnameInput) {
        query += '&surname=' + surnameInput;
    }

    const facultyInput = $('.search-tool input[name="faculty"]').val();
    if (facultyInput) {
        query += '&faculty=' + facultyInput;
    }

    const courseInput = $('.search-tool input[name="course"]').val();
    if (courseInput) {
        query += '&course=' + courseInput;
    }

    const groupInput = $('.search-tool input[name="group"]').val();
    if (groupInput) {
        query += '&group=' + groupInput;
    }

    const emailInput = $('.search-tool input[name="email"]').val();
    if (emailInput) {
        query += '&email=' + emailInput;
    }

    getStudents(query);
}

const displayStudentsData = function (data) {
    const tbody = $('#students-table tbody');
    tbody.empty();

    data.forEach(student => {
        drawSingleStudent(student)
    });
}

const drawSingleStudent = function (student) {
    const row = $('<tr>');
    row.append($('<td>').text(student.user_id));
    row.append($('<td>').text(student.user.full_name));
    row.append($('<td>').text(student.faculty.title));
    row.append($('<td>').text(student.course.course + ' курс'));
    row.append($('<td>').text(student.group.title));
    row.addClass(($('#students-table tbody tr').length + 1) % 2 === 0 ? 'row-gray' : 'row-beige');
    $('#students-table tbody').append(row);
}

module.exports = {
    getStudents,
    searchStudents,
    drawSingleStudent,
};
