const { showModal, hideModal, toggleTabsSideBar } = require('./../general.js');
const { searchGroups } = require('./common');

document.addEventListener('DOMContentLoaded', function () {
    toggleTabsSideBar('js-students');

    getStudents();

    $(document).on('click', '.js-search-students', searchStudents);

    $(document).on('click', '.js-add-student', addStudent);
    $(document).on('click', '.js-save-student', saveStudent);

    $(document).on('click', '.js-import-students', importStudents);
    $(document).on('click', '.js-import-students-save', importStudentsStore);
});

const getStudents = function (query = '') {
    $.ajax({
        url: '/api/university/' + universityId +'/students?' + query,
        method: 'GET',
        success: function (response) {
            displayStudentsData(response.data);
        },
        error: function (xhr, status, error) {
            console.error('Помилка:', error);
        }
    });
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

const addStudent = function (e) {
    searchFaculties();

    $('#addStudentModal .js-faculty').on('change', function() {
        const selectedFacultyId = $(this).val();
        searchCourses(selectedFacultyId);
    });

    $('#addStudentModal .js-course').on('change', function () {
        searchGroups({ courseId: $(this).val() }, fillGroupSelect)
    });
    showModal('addStudentModal');
};

const fillGroupSelect = function (groups) {
    const groupsSelect = $('#addStudentModal').find('.js-group');
    groupsSelect.empty();
    groupsSelect.append($('<option>').attr('value', '').text('Виберіть групу'));

    groups.forEach(group => {
        groupsSelect.append($('<option>').attr('value', group.id).text(group.title));
    });

    groupsSelect.trigger('click');
}

const saveStudent = function (e) {
    const modal = $('#addStudentModal');

    $.ajax({
        url: '/api/university/' + universityId + '/students',
        method: 'POST',
        data: {
            first_name: modal.find('.js-first-name').val(),
            last_name: modal.find('.js-last-name').val(),
            email: modal.find('.js-email').val(),
            phone_number: modal.find('.js-phone-number').val(),
            faculty_id: modal.find('.js-faculty').val(),
            course_id: modal.find('.js-course').val(),
            group_id: modal.find('.js-group').val(),
            _token: $(e.target).data('token'),
        },
        success: function (response) {
            drawSingleStudent(response.data);
            hideModal('addStudentModal')
            modal.find('input').val('');
            modal.find('select').val('');
        },
        error: function (response) {
            if (response.responseJSON.errors) {
                Object.entries(response.responseJSON.errors).forEach(function([key, errorMessage]) {
                    const errorParagraph = $('#addStudentModal').find(`p.error-message.${key}-error-message`);
                    errorParagraph.text(errorMessage);
                });
            }
        }
    });
}

const importStudents = function (e) {
    const inputsField = `<div class="js-form-import">
        <input type="file" class="form-control js-students-file">
    </div>`;
    $(inputsField).insertBefore($('#courseInfo').find('.js-new-student-form .js-save-student'));
    // $(e.target).addClass('hidden'); // add disable class
    $('.js-import-students-save').removeClass('hidden');
}

const importStudentsStore = function (e) {
    const modal = $('#courseInfo');
    let formData = new FormData();

    formData.append('students_file', $('.js-students-file')[0].files[0]);
    formData.append('_token', $(e.target).data('token'));

    $.ajax({
        url: '/api/university/' + universityId + '/faculty/' + modal.data('facultyid') +'/course/' + modal.data('courseid') +'/group/' + modal.data('groupid') + '/students-import',
        method: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            modal.find('.js-form-fields').remove();
            modal.find('.js-students-content').append(`<p>` + response.data.user.full_name +`</p>`);
            $(e.target).addClass('hidden');
            // $('.js-add-student').removeClass('hidden'); remove disable class
        },
        error: function (xhr, status, error) {
            console.error('Помилка:', error);
        }
    });
}

const searchFaculties = function () {
    $.ajax({
        url: '/api/university/' + universityId + '/faculties',
        method: 'GET',
        success: function (response) {
            const facultySelect = $('#addStudentModal').find('.js-faculty');
            facultySelect.empty();
            facultySelect.append($('<option>').attr('value', '').text('Виберіть факультет'));

            response.data.faculties.forEach(faculty => {
                facultySelect.append($('<option>').attr('value', faculty.id).text(faculty.title));
            });

            facultySelect.trigger('click');
        },
        error: function (xhr, status, error) {
            console.error('Помилка:', error);
        }
    });
}

const searchCourses = function (facultyId) {
    $.ajax({
        url: '/api/university/' + universityId + '/courses?facultyId=' + facultyId,
        method: 'GET',
        success: function (response) {
            const coursesSelect = $('#addStudentModal').find('.js-course');
            coursesSelect.empty();
            coursesSelect.append($('<option>').attr('value', '').text('Виберіть курс'));

            response.data.forEach(course => {
                coursesSelect.append($('<option>').attr('value', course.id).text(course.course + ' курс'));
            });

            coursesSelect.trigger('click');
        },
        error: function (xhr, status, error) {
            console.error('Помилка:', error);
        }
    });
}
