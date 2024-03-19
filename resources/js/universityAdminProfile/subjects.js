const { showModal, hideModal, toggleTabsSideBar } = require('./../general.js');

document.addEventListener('DOMContentLoaded', function () {
    toggleTabsSideBar('js-subjects');

    getSubjects();

    $(document).on('click', '.js-add-subject', addSubject);
    $(document).on('click', '.js-save-subject', saveSubject);
    $(document).on('click', '.js-edit-subject', editSubject);
})

const getSubjects = function () {
    $.ajax({
        url: '/api/university/' + universityId +'/subjects',
        method: 'GET',
        success: function (response) {
            displaySubjectsData(response.data);
        },
        error: function (xhr, status, error) {
            console.error('Помилка:', error);
        }
    });
}

const displaySubjectsData = function (data) {
    const tbody = $('#subjects-table tbody');
    tbody.empty();

    data.forEach(subject => {
        drawSingleSubject(subject)
    });
}

const addSubject = function (e) {
    $('#addEditSubjectModal .js-search-teacher-btn').on('click', searchTeachers);
    showModal('addEditSubjectModal');
}

const editSubject = function (e) {
    $('#addEditSubjectModal .js-search-teacher-btn').on('click', searchTeachers);
    $('#addEditSubjectModal').attr('data-subjectid', $(e.target).data('subjectid'));
    const row = $(e.target).closest('tr');

    $('#addEditSubjectModal .js-subject-title').val(row.find('.js-single-subject-title').text());

    const teachersList = row.find('.js-subject-teachers');
    const editTeachersList = $('#addEditSubjectModal .js-teachers-list ul');

    teachersList.find('li').each(function() {
        const listItem = $(`<li data-id="` + $(this).data('id') + `">`).text($(this).text());
        const deleteIcon = $('<i>').addClass('fas fa-times js-delete-teacher');
        listItem.append(deleteIcon);
        editTeachersList.append(listItem);
    });

    initRemoveTeacherClick();

    showModal('addEditSubjectModal');
}

const saveSubject = function (e) {
    let method = 'POST';
    let url = '/api/university/'+ universityId +'/subject';
    const teacherIds = $('#addEditSubjectModal .js-teachers-list li').map(function() {
        return $(this).data('id');
    }).get();

    const subjectId = $('#addEditSubjectModal').attr('data-subjectid');
    if (subjectId) {
        method = 'PUT';
        url = '/api/university/'+ universityId +'/subject/' + subjectId;
    }
    $.ajax({
        url: url,
        method: method,
        data: {
            title: $('#addEditSubjectModal .js-subject-title').val(),
            teachersIds: teacherIds,
            _token: $(e.target).data('token'),
        },
        success: function (response) {
            drawSingleSubject(response.data);
            $('#addEditSubjectModal').removeAttr('data-subjectid');
            $('#addEditSubjectModal .js-subject-title').val('');
            $('#addEditSubjectModal .js-teachers-list ul').empty();

            hideModal('addEditSubjectModal');
        },
        error: function (response) {
            if (response.responseJSON.errors) {
                Object.entries(response.responseJSON.errors).forEach(function([key, errorMessage]) {
                    const errorParagraph = $('#addEditSubjectModal').find(`p.error-message.${key}-error-message`);
                    errorParagraph.text(errorMessage);
                });
            }
        }
    });
}

const drawSingleSubject = function (subject) {
    const existingRow = $('#subjects-table tbody tr[data-id="' + subject.id + '"]');
    if (existingRow.length > 0) {
        existingRow.find('.js-single-subject-title').text(subject.title);
        const teachersList = existingRow.find('.js-subject-teachers');
        teachersList.empty();
        subject.teachers.forEach(teacher => {
            const listItem = $('<li>').addClass('list-course-item').attr('data-id', teacher.id).text(teacher.user.full_name);
            teachersList.append(listItem);
        });
    } else {
        const newRow = $('<tr>').attr('data-id', subject.id);
        newRow.append($('<td>').text(subject.id));
        newRow.append($('<td>').addClass('js-single-subject-title').text(subject.title));
        const teachersList = $('<ul>').addClass('js-subject-teachers');
        subject.teachers.forEach(teacher => {
            const listItem = $('<li>').addClass('list-course-item').attr('data-id', teacher.id).text(teacher.user.full_name);
            teachersList.append(listItem);
        });
        newRow.append($('<td>').append(teachersList));
        const addActionCell = $('<td>');
        const addActionIcon = $('<i>').addClass('fas fa-edit action-icon js-edit-subject').attr('title', 'Редагувати').attr('data-subjectid', subject.id);
        addActionCell.append(addActionIcon);
        newRow.append(addActionCell);
        newRow.addClass(($('#subjects-table tbody tr').length + 1) % 2 === 0 ? 'row-gray' : 'row-beige');
        $('#subjects-table tbody').append(newRow);
    }
}

const initTeachersSelectClick = function (teachersSelect) {
    teachersSelect.on('change', function() {
        const selectedTeacherId = $(this).val();
        const selectedTeacherName = $(this).find('option:selected').text();

        const teachersList = $('#addEditSubjectModal .js-teachers-list ul');
        const listItem = $(`<li data-id="` + selectedTeacherId + `">`).text(selectedTeacherName);
        const deleteIcon = $('<i>').addClass('fas fa-times js-delete-teacher');
        listItem.append(deleteIcon);
        teachersList.append(listItem);

        $(this).find('option:selected').hide();
    });
}

const initRemoveTeacherClick = function () {
    $('#addEditSubjectModal .js-teachers-list').on('click', '.js-delete-teacher', function() {
        const teacherId = parseInt($(this).parent().data('id'), 10);
        $('#addEditSubjectModal .js-teachers-select').find('option').each(function() {
            if ($(this).val() !== teacherId) {
                $(this).show();
            }
        });
        $(this).parent().remove();
    });
}

const searchTeachers = function () {
    const searchText = $('#addEditSubjectModal .js-teacher-search').val();

    $.ajax({
        url: '/api/university/' + universityId +'/teachers?searchText=' + searchText,
        method: 'GET',
        success: function (response) {
            const teachersSelect = $('#addEditSubjectModal').find('.js-teachers-select');
            teachersSelect.empty();
            teachersSelect.append($('<option >').attr('value', '').text());

            response.data.forEach(teacher => {
                teachersSelect.append($('<option class="js-teacher-item">').attr('value', teacher.user_id).text(teacher.user.full_name));
            });

            initTeachersSelectClick(teachersSelect);
            initRemoveTeacherClick();
            teachersSelect.removeClass('hidden');
        },
        error: function (xhr, status, error) {
            console.error('Помилка:', error);
        }
    });
}
