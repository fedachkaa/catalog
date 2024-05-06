const {
    showModal,
    hideModal,
    toggleTabsSideBar,
    showSpinner,
    hideSpinner,
    showErrors,
    initPagination
} = require('./../general.js');
const { searchTeachers, initRemoveTeacherClick } =  require('./common.js');

document.addEventListener('DOMContentLoaded', function () {
    toggleTabsSideBar('js-subjects');

    getSubjects();

    $(document).on('click', '.js-add-subject', addSubject);
    $(document).on('click', '.js-save-subject', saveSubject);
    $(document).on('click', '.js-edit-subject', editSubject);
    $(document).on('click', '.js-delete-subject', deleteSubject);
})

const getSubjects = function (searchParams = {}) {
    showSpinner();

    const queryString = Object.keys(searchParams)
        .map(key => `${encodeURIComponent(key)}=${encodeURIComponent(searchParams[key])}`)
        .join('&');

    $.ajax({
        url: '/api/university/' + universityId +'/subjects?' + queryString,
        method: 'GET',
        success: function (response) {
            if (response.data.subjects.length) {
                displaySubjectsData(response.data.subjects);
                prepareSubjectsTable(true);

                initPagination(response.data.pagination);
                $('.js-pagination .pagination-first').off().on('click', function () {
                    getSubjects();
                });
                $('.js-pagination .pagination-next').off().on('click', function () {
                    getSubjects({page: response.data.pagination.next});
                });
                $('.js-pagination .pagination-previous').off().on('click', function () {
                    getSubjects({page: response.data.pagination.before});
                });
                $('.js-pagination .pagination-last').off().on('click', function () {
                    getSubjects({page: response.data.pagination.last});
                });
            } else {
                prepareSubjectsTable(false);
            }

            hideSpinner();
        },
        error: function (xhr, status, error) {
            console.error('Помилка:', error);
            hideSpinner();
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
    $('#addEditSubjectModal .js-search-teacher-btn').on('click', function() {
        searchTeachers('#addEditSubjectModal', { searchText: $('#addEditSubjectModal .js-teacher-search').val() });
    });
    showModal('addEditSubjectModal');
}

const editSubject = function (e) {
    $('#addEditSubjectModal .js-search-teacher-btn').on('click', function() {
        searchTeachers('#addEditSubjectModal', { searchText: $('#addEditSubjectModal .js-teacher-search').val() });
    });

    const row = $(e.target).closest('tr');

    $('#addEditSubjectModal').attr('data-subjectid', row.data('subjectid'));

    $('#addEditSubjectModal .js-subject-title').val(row.find('.js-single-subject-title').text());

    const teachersList = row.find('.js-subject-teachers');
    const editTeachersList = $('#addEditSubjectModal .js-teachers-list ul');

    teachersList.find('li').each(function() {
        const listItem = $(`<li data-id="` + $(this).data('id') + `">`).text($(this).text());
        const deleteIcon = $('<i>').addClass('fas fa-times js-delete-teacher');
        listItem.append(deleteIcon);
        editTeachersList.append(listItem);
    });

    initRemoveTeacherClick('addEditSubjectModal');

    showModal('addEditSubjectModal');
}

const saveSubject = function (e) {
    showSpinner();

    let method = 'POST';
    let url = '/api/university/'+ universityId +'/subjects';
    const teacherIds = $('#addEditSubjectModal .js-teachers-list li').map(function() {
        return $(this).data('teacherid');
    }).get();

    const subjectId = $('#addEditSubjectModal').attr('data-subjectid');
    if (subjectId) {
        method = 'PUT';
        url = '/api/university/'+ universityId +'/subjects/' + subjectId;
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
            prepareSubjectsTable(true);

            drawSingleSubject(response.data);
            $('#addEditSubjectModal').removeAttr('data-subjectid');
            $('#addEditSubjectModal .js-subject-title').val('');
            $('#addEditSubjectModal .js-teachers-list ul').empty();

            hideModal('addEditSubjectModal');
            hideSpinner();
        },
        error: function (response) {
            showErrors(response.responseJSON.errors, '#addEditSubjectModal');
            hideSpinner();
        }
    });
}

const prepareSubjectsTable = function (isShow = false) {
    if (isShow) {
        $('#subjects-table').removeClass('hidden');
        $('.js-subjects-message').text('');
        $('.js-pagination').removeClass('hidden');
    } else {
        $('#subjects-table').addClass('hidden');
        $('.js-pagination').addClass('hidden');
        $('.js-subjects-message').append('<p>Ще немає предметів</p>')
    }
}

const drawSingleSubject = function (subject) {
    const existingRow = $('#subjects-table tbody tr[data-subject="' + subject.id + '"]');
    if (existingRow.length > 0) {
        existingRow.find('.js-single-subject-title').text(subject.title);
        const teachersList = existingRow.find('.js-subject-teachers');
        teachersList.empty();
        subject.teachers.forEach(teacher => {
            const listItem = $('<li>').addClass('list-course-item').attr('data-id', teacher.id).text(teacher.user.full_name);
            teachersList.append(listItem);
        });
    } else {
        const newRow = $('<tr>').attr('data-subject', subject.id);
        newRow.append($('<td>').text(subject.id));
        newRow.append($('<td>').addClass('js-single-subject-title').text(subject.title));
        const teachersList = $('<ul>').addClass('js-subject-teachers');
        subject.teachers.forEach(teacher => {
            const listItem = $('<li>').addClass('list-course-item').attr('data-id', teacher.id).text(teacher.user.full_name);
            teachersList.append(listItem);
        });
        newRow.append($('<td>').append(teachersList));
        const addActionCell = $('<td>')
            .append($('<i>').addClass('fas fa-edit action-icon js-edit-subject').attr('title', 'Редагувати'))
            .append($('<i>').addClass('fas fa-trash action-icon js-delete-subject').attr('title', 'Видалити'))

        newRow.append(addActionCell);
        newRow.addClass(($('#subjects-table tbody tr').length + 1) % 2 === 0 ? 'row-gray' : 'row-beige');
        $('#subjects-table tbody').append(newRow);
    }
}

const deleteSubject = function (e) {
    if (!confirm("Are you sure you want to delete this subject?")) {
        return;
    }

    const subjectId = $(e.target).closest('tr').data('subject');

    $.ajax({
        url: '/api/university/' + universityId + '/subjects/' + subjectId,
        method: 'DELETE',
        data: {
            _token: $(e.target).closest('#subjects-table').data('token'),
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
