const { showModal, hideModal, toggleTabsSideBar, showSpinner, hideSpinner, showErrors, clearModal, getUserBaseInfo } = require('./../general.js');
const { searchGroups, searchFaculties, searchCourses } = require('./common.js');
const { searchStudents, getStudents, drawSingleStudent, prepareStudentsTable } = require('../common/students.js');

document.addEventListener('DOMContentLoaded', function () {
    toggleTabsSideBar('js-students');

    getStudents();

    $(document).on('click', '.js-search-students', searchStudents);

    $(document).on('click', '.js-add-student', addStudent);
    $(document).on('click', '.js-save-student', saveStudent);
    $(document).on('click', '.js-edit-student', editStudent);
    $(document).on('click', '.js-delete-student', deleteStudent);

    $(document).on('click', '.js-show-user-info', showUserInfo);

    $(document).on('click', '.js-import-students', importStudents);
    $(document).on('click', '.js-import-students-save', importStudentsStore);
});

const showUserInfo = function (e) {
    getUserBaseInfo($(e.target).closest('tr').data('userid'));
}

const addStudent = function (e) {
    initAddEditStudentModal();
    showModal('addStudentModal');
};

const initAddEditStudentModal = function () {
    searchFaculties('addStudentModal');

    $('#addStudentModal .js-faculty').on('change', function() {
        const selectedFacultyId = $(this).val();
        searchCourses(selectedFacultyId, 'addStudentModal');
    });

    $('#addStudentModal .js-course').on('change', function () {
        searchGroups({ courseId: $(this).val() }, 'addStudentModal', fillGroupSelect);
    });
}

const editStudent = function (e) {
    showSpinner();

    initAddEditStudentModal();

    $.ajax({
        url: '/api/university/' + universityId + '/students/' + $(e.target).closest('tr').data('userid'),
        method: 'GET',
        success: function (response) {
            const addEditStudentModal = $('#addStudentModal');
            addEditStudentModal.attr('data-studentid', response.data.user_id);
            addEditStudentModal.find('.js-first-name').val(response.data.user.first_name);
            addEditStudentModal.find('.js-last-name').val(response.data.user.last_name);
            addEditStudentModal.find('.js-email').val(response.data.user.email);
            addEditStudentModal.find('.js-phone-number').val(response.data.user.phone_number);

            addEditStudentModal.find('.js-faculty option[value="' + response.data.faculty.id + '"]').attr('selected', 'selected');

            searchCourses(response.data.faculty.id, 'addStudentModal');
            searchGroups({ courseId: response.data.course.id }, 'addStudentModal', fillGroupSelect);

            setTimeout(function() {
                addEditStudentModal.find('.js-course option[value="' + response.data.course.id + '"]').attr('selected', 'selected');
                addEditStudentModal.find('.js-group option[value="' + response.data.group_id + '"]').attr('selected', 'selected');
            }, 2000);

            hideSpinner();
            showModal('addStudentModal');
        },
        error: function (xhr, status, error) {
            hideSpinner();
            console.error('Помилка:', error);
        }
    });
}

const deleteStudent = function (e) {
    if (!confirm("Are you sure you want to delete this student?")) {
        return;
    }

    const studentId = $(e.target).closest('tr').data('userid');

    $.ajax({
        url: '/api/university/' + universityId + '/students/' + studentId,
        method: 'DELETE',
        data: {
            _token: $(e.target).closest('#students-table').data('token'),
        },
        success: function (response) {
            $(e.target).closest('tr').remove();
            hideSpinner();
        },
        error: function (response) {
            console.error(response);
            hideSpinner();
        }
    });
}

const fillGroupSelect = function (groups, block) {
    const groupsSelect = $('#' + block).find('.js-group');
    groupsSelect.empty();
    groupsSelect.append($('<option>').attr('value', '').text('Виберіть групу'));

    groups.forEach(group => {
        groupsSelect.append($('<option>').attr('value', group.id).text(group.title));
    });
}

const saveStudent = function (e) {
    showSpinner();
    const modal = $('#addStudentModal');

    const studentId =modal.data('studentid');
    let url = '/api/university/' + universityId + '/students';
    let method = 'POST';
    if (studentId) {
        url = '/api/university/' + universityId + '/students/' + studentId;
        method = 'PUT';
    }

    $.ajax({
        url: url,
        method: method,
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
            prepareStudentsTable(true);
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
