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

const drawSingleStudent = (student) => {
    const existingRow = $('#students-table tbody tr[data-userid="' + student.user_id + '"]');
    if (existingRow.length > 0) {
        existingRow.find('.js-student-name').text(student.user.full_name);
        existingRow.find('.js-student-faculty').text(student.faculty.title);
        existingRow.find('.js-student-course').text(student.course.course + ' курс');
        existingRow.find('.js-student-group').text(student.group.title);
    } else {
        const newRow = createStudentRow(student);
        $('#students-table tbody').append(newRow);
    }
};

const createStudentRow = function (student) {
    const row = $('<tr>').attr('data-userid', student.user_id);
    row.append($('<td>').text(student.user_id));
    row.append($('<td class="js-show-user-info js-student-name action-icon">').text(student.user.full_name));
    row.append($('<td class="js-student-faculty">').text(student.faculty.title));
    row.append($('<td class="js-student-course">').text(student.course.course + ' курс'));
    row.append($('<td class="js-student-group">').text(student.group.title));

    row.append($('<td>')
        .append($('<i>').addClass('fas fa-edit action-icon js-edit-student'))
        .append($('<i>').addClass('fas fa-trash action-icon js-delete-student'))
    );

    row.addClass(($('#students-table tbody tr').length + 1) % 2 === 0 ? 'row-gray' : 'row-beige');

    return row;
}

module.exports = {
    getStudents,
    searchStudents,
    drawSingleStudent,
};
