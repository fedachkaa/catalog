const { showModal, hideModal } = require('../general.js');
const {toggleTabsSideBar} = require("../general");

document.addEventListener('DOMContentLoaded', function () {
    toggleTabsSideBar('js-teachers');
    getTeachers();

    $(document).on('click', '.js-add-teacher', addTeacher);
    $(document).on('click', '.js-save-teacher', saveTeacher);
})

const getTeachers = function () {
    $.ajax({
        url: '/api/university/' + 1 +'/teachers',
        method: 'GET',
        success: function (response) {
            console.log(response);
            displayTeachersData(response.data);
        },
        error: function (xhr, status, error) {
            console.error('Помилка:', error);
        }
    });
}

const displayTeachersData = function (data) {
    const tbody = $('#teachers-table tbody');
    tbody.empty();

    data.forEach((teacher, id) => {
        const row = $('<tr>');
        row.append($('<td>').text(teacher.user_id));
        row.append($('<td>').text(teacher.user.full_name));
        row.append($('<td>').text(teacher.faculty.title));

        const subjectsList = $('<ul>').addClass('js-teacher-subjects');
        teacher.subjects.forEach(subject => {
            const listItem = $('<li>').addClass('list-course-item').attr('data-id', subject.id).text(subject.title);
            subjectsList.append(listItem);
        });
        row.append($('<td>').append(subjectsList));

        const actionsCell = $('<td>');
        const editIcon = $('<i>').addClass('fas fa-edit action-icon');
        const deleteIcon = $('<i>').addClass('fas fa-trash action-icon');
        actionsCell.append(editIcon, deleteIcon);
        row.append(actionsCell);

        row.addClass(id % 2 === 0 ? 'row-gray' : 'row-beige');
        tbody.append(row);
    });
}

const addTeacher = function (e) {
    $.ajax({
        url: '/api/university/' + universityId +'/faculties',
        method: 'GET',
        success: function (response) {
            const facultySelect = $('#addTeacherModal').find('.js-faculty');
            facultySelect.empty();
            facultySelect.append($('<option>').attr('value', '').text('Виберіть факультет'));

            response.data.faculties.forEach(faculty => {
                facultySelect.append($('<option>').attr('value', faculty.id).text(faculty.title));
            });
        },
        error: function (xhr, status, error) {
            console.error('Помилка:', error);
        }
    });

    showModal('addTeacherModal');
};

const saveTeacher = function (e) {
    const teacherModal = $('#addTeacherModal');

    $.ajax({
        url: '/api/university/' + universityId + '/teachers',
        method: 'POST',
        data: {
            first_name: teacherModal.find('.js-first-name').val(),
            last_name: teacherModal.find('.js-last-name').val(),
            email: teacherModal.find('.js-email').val(),
            phone_number: teacherModal.find('.js-phone-number').val(),
            // subject: teacherModal.find('.js-subject').val(),
            faculty_id: teacherModal.find('.js-faculty').val(),
            _token: $(e.target).data('token'),
        },
        success: function (response) {
            const teachersTableBody = $('#teachers-table tbody');

            const newRow = $('<tr>');
            newRow.append($('<td>').text(response.data.user.full_name));
            newRow.append($('<td>').text(response.data.faculty.title));
            newRow.append($('<td>').text('Subjects will be soon ...'));
            const actionsCell = $('<td>');
            const editIcon = $('<i>').addClass('fas fa-edit edit-icon');
            const deleteIcon = $('<i>').addClass('fas fa-trash delete-icon');
            actionsCell.append(editIcon, deleteIcon);
            newRow.append(actionsCell);

            teachersTableBody.append(newRow);

            hideModal('addTeacherModal');
        },
        error: function (xhr, status, error) {
            console.error('Помилка:', error);
        }
    });
}
