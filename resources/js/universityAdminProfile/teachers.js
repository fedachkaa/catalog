const { showModal, hideModal, toggleTabsSideBar, showSpinner, hideSpinner, getUserBaseInfo} = require('../general.js');
const { searchFaculties } = require('./common.js');

document.addEventListener('DOMContentLoaded', function () {
    toggleTabsSideBar('js-teachers');
    getTeachers();

    $(document).on('click', '.js-add-teacher', addTeacher);
    $(document).on('click', '.js-save-teacher', saveTeacher);
    $(document).on('click', '.js-show-user-info', showUserInfo)
});

const showUserInfo = function (e) {
    getUserBaseInfo($(e.target).closest('tr').data('userid'));
}

const getTeachers = function () {
    showSpinner();

    $.ajax({
        url: '/api/university/' + 1 +'/teachers',
        method: 'GET',
        success: function (response) {
            displayTeachersData(response.data);
            hideSpinner();
        },
        error: function (xhr, status, error) {
            hideSpinner();
            console.error('Помилка:', error);
        }
    });
}

const displayTeachersData = function (data) {
    const tbody = $('#teachers-table tbody');
    tbody.empty();

    data.forEach((teacher, id) => {
        displaySingleTeacher(teacher);
    });
}

const addTeacher = function (e) {
    searchFaculties('addTeacherModal');
    $('#addTeacherModal .js-search-subject-btn').on('click', function() {
        searchSubjects();
    });

    showModal('addTeacherModal');
};

const saveTeacher = function (e) {
    showSpinner();

    const teacherModal = $('#addTeacherModal');

    const subjectsIds = teacherModal.find('.js-subject-list li').map(function() {
        return $(this).data('subjectid');
    }).get();

    $.ajax({
        url: '/api/university/' + universityId + '/teachers',
        method: 'POST',
        data: {
            first_name: teacherModal.find('.js-first-name').val(),
            last_name: teacherModal.find('.js-last-name').val(),
            email: teacherModal.find('.js-email').val(),
            phone_number: teacherModal.find('.js-phone-number').val(),
            subjectsIds: subjectsIds,
            faculty_id: teacherModal.find('.js-faculty').val(),
            _token: $(e.target).data('token'),
        },
        success: function (response) {
            displaySingleTeacher(response.data);
            hideSpinner();
            hideModal('addTeacherModal');
        },
        error: function (xhr, status, error) {
            hideSpinner();
            console.error('Помилка:', error);
        }
    });
}

const searchSubjects = function () {
    showSpinner();

    $.ajax({
        url: '/api/university/' + universityId + '/subjects?searchText=' + $('#addTeacherModal .js-subject-search').val(),
        method: 'GET',
        success: function (response) {
            const subjectsSelect = $('#addTeacherModal').find('.js-subjects-select');
            subjectsSelect.empty();
            subjectsSelect.append($('<option>').attr('value', '').text('Виберіть предмет'));
            response.data.forEach(subject => {
                subjectsSelect.append($('<option>').attr('value', subject.id).text(subject.title));
            });
            initSubjectClick();
            initSubjectDelete();
            subjectsSelect.removeClass('hidden');
            hideSpinner();
        },
        error: function (xhr, status, error) {
            console.error('Помилка:', error);
            hideSpinner();
        }
    });
}

const initSubjectClick = function () {
    $('#addTeacherModal .js-subjects-select').on('change', function () {
        const selectedSubjectId = $(this).val();
        const selectedSubjectName = $(this).find('option:selected').text();

        const subjectsList = $('#addTeacherModal .js-subject-list ul');
        const listItem = $(`<li data-subjectid="` + selectedSubjectId + `">`).text(selectedSubjectName);
        const deleteIcon = $('<i>').addClass('fas fa-times js-delete-subject');
        listItem.append(deleteIcon);
        subjectsList.append(listItem);

        $(this).find('option:selected').hide();
        $(this).val('');
    });
}

const initSubjectDelete = function () {
    $('#addTeacherModal .js-subject-list').on('click', '.js-delete-subject', function() {
        const subjectId = parseInt($(this).parent().data('subjectid'), 10);
        $('#addTeacherModal .js-subjects-select').find('option').each(function() {
            if (parseInt($(this).val(), 10) === subjectId) {
                $(this).show();
            }
        });
        $(this).parent().remove();
    });
}

const displaySingleTeacher = function (teacher) {
    const newRow = $('<tr>').attr('data-userid', teacher.user_id );
    newRow.append($('<td>').text(teacher.user_id));
    newRow.append($('<td class="action-icon js-show-user-info">').text(teacher.user.full_name));
    newRow.append($('<td>').text(teacher.faculty.title));
    const subjectsList = $('<ul>').addClass('js-teacher-subjects');
    teacher.subjects.forEach(subject => {
        const listItem = $('<li>').addClass('list-course-item').attr('data-id', subject.id).text(subject.title);
        subjectsList.append(listItem);
    });
    newRow.append($('<td>').append(subjectsList));

    const actionsCell = $('<td>');
    const editIcon = $('<i>').addClass('fas fa-edit edit-icon js-edit-teacher');
    const deleteIcon = $('<i>').addClass('fas fa-trash delete-icon js-delete-teacher');
    actionsCell.append(editIcon, deleteIcon);
    newRow.append(actionsCell);
    newRow.addClass(($('#teachers-table tbody tr').length + 1) % 2 === 0 ? 'row-gray' : 'row-beige');

    $('#teachers-table tbody').append(newRow);
}
