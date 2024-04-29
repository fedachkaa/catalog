const { showModal, hideModal, toggleTabsSideBar, showSpinner, hideSpinner, showErrors, clearModal, getUserBaseInfo } = require('./../general.js');
const { searchGroups, searchFaculties, searchCourses } = require('./common.js');
const { searchStudents, getStudents, drawSingleStudent } = require('../common/students.js');

document.addEventListener('DOMContentLoaded', function () {
    toggleTabsSideBar('js-students');

    getStudents();

    $(document).on('click', '.js-search-students', searchStudents);

    $(document).on('click', '.js-add-student', addStudent);
    $(document).on('click', '.js-save-student', saveStudent);

    $(document).on('click', '.js-show-user-info', showUserInfo);

    $(document).on('click', '.js-import-students', importStudents);
    $(document).on('click', '.js-import-students-save', importStudentsStore);
});

const showUserInfo = function (e) {
    getUserBaseInfo($(e.target).closest('tr').data('userid'));
}

const addStudent = function (e) {
    searchFaculties('addStudentModal');

    $('#addStudentModal .js-faculty').on('change', function() {
        const selectedFacultyId = $(this).val();
        searchCourses(selectedFacultyId, 'addStudentModal');
    });

    $('#addStudentModal .js-course').on('change', function () {
        searchGroups({ courseId: $(this).val() }, 'addStudentModal', fillGroupSelect);
    });
    showModal('addStudentModal');
};

const fillGroupSelect = function (groups, block) {
    const groupsSelect = $('#' + block).find('.js-group');
    groupsSelect.empty();
    groupsSelect.append($('<option>').attr('value', '').text('Виберіть групу'));

    groups.forEach(group => {
        groupsSelect.append($('<option>').attr('value', group.id).text(group.title));
    });

    groupsSelect.trigger('click');
}

const saveStudent = function (e) {
    showSpinner();

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
            clearModal('addStudentModal');
            hideSpinner();
        },
        error: function (response) {
            showErrors(response.responseJSON.errors, '#addStudentModal');
            hideSpinner();
        }
    });
}

const importStudents = function (e) {
    searchFaculties('importStudentModal');

    $('#importStudentModal .js-faculty').on('change', function() {
        const selectedFacultyId = $(this).val();
        searchCourses(selectedFacultyId, 'importStudentModal');
    });

    $('#importStudentModal .js-course').on('change', function () {
        searchGroups({ courseId: $(this).val() }, 'importStudentModal', fillGroupSelect);

    });
    showModal('importStudentModal');
}

const importStudentsStore = function (e) {
    showSpinner();

    const modal = $('#importStudentModal');
    let formData = new FormData();

    formData.append('faculty_id', modal.find('.js-faculty').val());
    formData.append('course_id', modal.find('.js-course').val());
    formData.append('group_id', modal.find('.js-group').val());
    formData.append('students_file', $('.js-students-file')[0].files[0]);
    formData.append('_token', $(e.target).data('token'));

    $.ajax({
        url: '/api/university/' + universityId + '/students-import',
        method: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            modal.find('.js-form-fields').remove();
            response.data.forEach(student => {
                modal.find('.js-students-content').append(`<p>` + student.user.full_name +`</p>`);
            });
            $(e.target).addClass('hidden');
            hideSpinner();
        },
        error: function (xhr, status, error) {
            console.error('Помилка:', error);
            hideSpinner();
        }
    });
}
