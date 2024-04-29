const { showErrors, showModal, hideModal, clearModal, toggleTabsSideBar, showSpinner, hideSpinner, getUserBaseInfo} = require('../general.js');
const { searchFaculties } = require('./common.js');

document.addEventListener('DOMContentLoaded', function () {
    toggleTabsSideBar('js-teachers');
    getTeachers();

    $(document).on('click', '.js-add-teacher', addTeacher);
    $(document).on('click', '.js-save-teacher', saveTeacher);
    $(document).on('click', '.js-edit-teacher', editTeacher);
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

    data.forEach(teacher => {
        displaySingleTeacher(teacher);
    });
};

const initSubjectActions = function() {
    $('#addTeacherModal .js-search-subject-btn').on('click', function() {
        searchSubjects();
    });
    initSubjectClick();
    initSubjectDelete();
};

const addTeacher = function (e) {
    searchFaculties('addTeacherModal');
    initSubjectActions();
    showModal('addTeacherModal');
};

const editTeacher = function (e) {
    showSpinner();

    searchFaculties('addTeacherModal');
    $.ajax({
        url: '/api/university/' + universityId + '/teachers/' + $(e.target).closest('tr').data('userid'),
        method: 'GET',
        success: function (response) {
            const addEditTeacherModal = $('#addTeacherModal');
            addEditTeacherModal.attr('data-teacherid', response.data.user_id);
            addEditTeacherModal.find('.js-first-name').val(response.data.user.first_name);
            addEditTeacherModal.find('.js-last-name').val(response.data.user.last_name)
            addEditTeacherModal.find('.js-email').val(response.data.user.email)
            addEditTeacherModal.find('.js-phone-number').val(response.data.user.phone_number)
            addEditTeacherModal.find('.js-faculty option[value="' + response.data.faculty_id + '"]').prop('selected', true);

            response.data.subjects.forEach(function (subject) {
                addEditTeacherModal.find('.js-subject-list').append(`<li data-subjectid="`+ subject.id+`">`
                    + subject.title +
                    `<i class="fas fa-times js-delete-subject"></i>`
                    +`</li>`)
            });
            initSubjectActions();
            hideSpinner();
            showModal('addTeacherModal');
        },
        error: function (xhr, status, error) {
            hideSpinner();
            console.error('Помилка:', error);
        }
    });
}

const saveTeacher = function (e) {
    showSpinner();

    const teacherModal = $('#addTeacherModal');
    const teacherId = teacherModal.attr('data-teacherid');

    let method = 'POST';
    let url = '/api/university/' + universityId + '/teachers';
    if (teacherId) {
        method = 'PUT';
        url =  '/api/university/' + universityId + '/teachers/' + teacherId;
    }

    const subjectsIds = teacherModal.find('.js-subject-list li').map(function() {
        return $(this).data('subjectid');
    }).get();

    $.ajax({
        url: url,
        method: method,
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
            clearModal('addTeacherModal');
            hideModal('addTeacherModal');
        },
        error: function (response) {
            showErrors(response.responseJSON.errors, '#addTeacherModal');
            hideSpinner();
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
    $('#addTeacherModal').on('change', '.js-subjects-select', function () {
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
    $('#addTeacherModal').on('click', '.js-subject-list .js-delete-subject', function() {
        const subjectId = parseInt($(this).parent().data('subjectid'), 10);
        $('#addTeacherModal .js-subjects-select').find('option').each(function() {
            if (parseInt($(this).val(), 10) === subjectId) {
                $(this).show();
            }
        });
        $(this).parent().remove();
    });
}

const createTeacherRow = (teacher) => {
    const newRow = $('<tr>').attr('data-userid', teacher.user_id);
    newRow.append($('<td>').text(teacher.user_id));
    newRow.append($('<td class="action-icon js-show-user-info js-teacher-name">').text(teacher.user.full_name));
    newRow.append($('<td>').text(teacher.faculty.title));

    const subjectsList = $('<ul>').addClass('js-teacher-subjects');
    updateSubjectsList(subjectsList, teacher.subjects);
    newRow.append($('<td>').append(subjectsList));

    const actionsCell = $('<td>');
    actionsCell.append($('<i>').addClass('fas fa-edit action-icon js-edit-teacher'));
    actionsCell.append($('<i>').addClass('fas fa-trash action-icon js-delete-teacher'));
    newRow.append(actionsCell);

    newRow.addClass(($('#teachers-table tbody tr').length + 1) % 2 === 0 ? 'row-gray' : 'row-beige');

    return newRow;
};

const displaySingleTeacher = (teacher) => {
    const existingRow = $('#teachers-table tbody tr[data-userid="' + teacher.user_id + '"]');
    if (existingRow.length > 0) {
        existingRow.find('.js-teacher-name').text(teacher.user.full_name);
        existingRow.find('.js-teacher-faculty').text(teacher.faculty.title);
        updateSubjectsList(existingRow.find('.js-teacher-subjects'), teacher.subjects);
    } else {
        const newRow = createTeacherRow(teacher);
        $('#teachers-table tbody').append(newRow);
    }
};

const updateSubjectsList = (container, subjects) => {
    container.empty();
    subjects.forEach(subject => {
        const listItem = $('<li>').addClass('list-course-item').attr('data-id', subject.id).text(subject.title);
        container.append(listItem);
    });
};

