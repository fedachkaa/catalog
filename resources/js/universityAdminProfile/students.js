const { showModal, hideModal, toggleTabsSideBar, showSpinner, hideSpinner } = require('./../general.js');
const { searchGroups, searchFaculties, searchCourses } = require('./common');
const { searchStudents, getStudents, drawSingleStudent } = require('../common/students.js');

document.addEventListener('DOMContentLoaded', function () {
    toggleTabsSideBar('js-students');

    getStudents();

    $(document).on('click', '.js-search-students', searchStudents);

    $(document).on('click', '.js-add-student', addStudent);
    $(document).on('click', '.js-save-student', saveStudent);

    $(document).on('click', '.js-import-students', importStudents);
    $(document).on('click', '.js-import-students-save', importStudentsStore);
});


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
            modal.find('input').val('');
            modal.find('select').val('');
            hideSpinner();
        },
        error: function (response) {
            if (response.responseJSON.errors) {
                Object.entries(response.responseJSON.errors).forEach(function([key, errorMessage]) {
                    const errorParagraph = $('#addStudentModal').find(`p.error-message.${key}-error-message`);
                    errorParagraph.text(errorMessage);
                });
            }
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
            modal.find('.js-students-content').append(`<p>` + response.data.user.full_name +`</p>`);
            $(e.target).addClass('hidden');
            hideSpinner();
        },
        error: function (xhr, status, error) {
            console.error('Помилка:', error);
            hideSpinner();
        }
    });
}
