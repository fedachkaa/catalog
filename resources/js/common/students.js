const { showSpinner, hideSpinner, initPagination } = require("../general");

const getStudents = function (query = '') {
    showSpinner();

    $.ajax({
        url: '/api/university/' + universityId +'/students?' + query,
        method: 'GET',
        success: function (response) {
            if (response.data.students.length) {
                displayStudentsData(response.data.students);
                prepareStudentsTable(true);
                initPagination(response.data.pagination);
                $('.js-pagination .pagination-first').off().on('click', function () {
                    getStudents();
                });
                $('.js-pagination .pagination-next').off().on('click', function () {
                    getStudents(`page=${response.data.pagination.next}`);
                });
                $('.js-pagination .pagination-previous').off().on('click', function () {
                    getStudents(`page=${response.data.pagination.before}`);
                });
                $('.js-pagination .pagination-last').off().on('click', function () {
                    getStudents(`page=${response.data.pagination.last}`);
                });
            } else {
                prepareStudentsTable(false);
            }
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

const createStudentRow = function (student, allowActions = true) {
    const row = $('<tr>').attr('data-userid', student.user_id);
    row.append($('<td>').text(student.user_id));
    row.append($('<td class="js-show-user-info js-student-name action-icon">').text(student.user.full_name));
    row.append($('<td class="js-student-faculty">').text(student.faculty.title));
    row.append($('<td class="js-student-course">').text(student.course.course + ' курс'));
    row.append($('<td class="js-student-group">').text(student.group.title));

    if (typeof teacherId !== 'undefined') {
        row.append($('<td>'));
    } else {
        row.append($('<td>')
            .append($('<i>').addClass('fas fa-edit action-icon js-edit-student'))
            .append($('<i>').addClass('fas fa-trash action-icon js-delete-student'))
        );
    }

    row.addClass(($('#students-table tbody tr').length + 1) % 2 === 0 ? 'row-gray' : 'row-beige');

    return row;
}

const prepareStudentsTable = function (isShow = false) {
    if (isShow) {
        $('#students-table').removeClass('hidden');
        $('.js-pagination').removeClass('hidden');
        $('.js-students-message').text('');
    } else {
        $('#students-table').addClass('hidden');
        $('.js-pagination').addClass('hidden');
        $('.js-students-message').append('<p>Ще немає студентів</p>')
    }
}

module.exports = {
    getStudents,
    searchStudents,
    drawSingleStudent,
    prepareStudentsTable,
};
