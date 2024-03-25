const { showModal, hideModal, clearModal, toggleTabsSideBar } = require('./../general.js');
const { searchGroups } = require('./common.js');

document.addEventListener('DOMContentLoaded', function () {
    toggleTabsSideBar('js-faculties');

    getFaculties();

    $(document).on('click', '.js-add-faculty', addFaculty);
    $(document).on('click', '.js-edit-faculty', editFaculty)
    $(document).on('click', '.js-save-faculty', saveFaculty);

    $(document).on('click', '.js-add-course', addCourse);
    $(document).on('click', '.js-save-course', saveCourse);
    $(document).on('click', '.js-view-course', getCourseGroups);
    $(document).on('click', '.js-view-group', getGroupStudents);

    $(document).on('click', '.js-add-group', addGroup);
    $(document).on('click', '.js-save-group', saveGroup);

    $(document).on('click', '.js-add-student', openAddStudent);
});

const openAddStudent = function () {
    const newWindow = window.open('/university/' + universityId + '/students', '_blank');
    newWindow.onload = function() {
        // TODO add open modal with faculty and course and group
        showModal('addStudentModal');
    };
}

const getFaculties = function() {
    $.ajax({
        url: '/api/university/' + universityId + '/faculties',
        method: 'GET',
        success: function (response) {
            displayFacultiesData(response.data);
        },
        error: function (xhr, status, error) {
            console.error('Помилка:', error);
        }
    });
}

const addFaculty = function(e) {
    showModal('addEditFacultyModal');
}

const editFaculty = function (e) {
    $('#addEditFacultyModal').attr('data-facultyid', $(e.target).data('facultyid'));
    const row = $(e.target).closest('tr');
    $('#addEditFacultyModal .js-faculty-title').val(row.find('.js-single-faculty-title').text());
    showModal('addEditFacultyModal');
}

const saveFaculty = function(e) {
    let method = 'POST';
    let url = '/api/university/'+ universityId +'/faculty/create';

    const modal = $('#addEditFacultyModal');
    const facultyId = modal.attr('data-facultyid');
    if (facultyId) {
        method = 'PUT';
        url = '/api/university/'+ universityId +'/faculty/' + facultyId;
    }
    $.ajax({
        url: url,
        method: method,
        data: {
            title: modal.find('.js-faculty-title').val(),
            _token: $(e.target).data('token'),
        },
        success: function (response) {
            drawSingleFaculty(response.data);
            hideModal('addEditFacultyModal');
            clearModal('addEditFacultyModal', ['facultyid']);
        },
        error: function (response) {
            if (response.responseJSON.errors) {
                Object.entries(response.responseJSON.errors).forEach(function([key, errorMessage]) {
                    const errorParagraph = $('#addEditFacultyModal').find(`p.error-message.${key}-error-message`);
                    errorParagraph.text(errorMessage);
                });
            }
        }
    });
}

const displayFacultiesData = function(data) {
    const tbody = $('#faculties-table tbody');
    tbody.empty();

    data.faculties.forEach((faculty, id) => {
        drawSingleFaculty(faculty);
    });
}

const drawSingleFaculty = function (faculty) {
    const existingRow = $('#faculties-table tbody tr[data-facultyid="' + faculty.id + '"]');
    if (existingRow.length > 0) {
        existingRow.find('.js-single-faculty-title').text(faculty.title);
    } else {
        const tbody = $('#faculties-table tbody');
        const row = $(`<tr data-facultyid=` + faculty.id + `>`);

        row.append($('<td>').text(faculty.id));
        row.append($('<td class="js-single-faculty-title">').text(faculty.title));

        const coursesList = $('<ul class="js-list-courses">');
        faculty.courses.forEach(course => {
            const listItem = $(`<li class="list-course-item js-view-course" data-id="` + course.id + `">`).text(course.course + ' курс');
            coursesList.append(listItem);
        });

        row.append($('<td>').append(coursesList));

        const addActionCell = $('<td>');
        const addActionIcon = $('<i>').addClass('fas fa-plus action-icon js-add-course')
            .attr('title', 'Додати курс')
            .attr('data-facultyid', faculty.id);
        const editFaculty = $('<i>').addClass('fas fa-edit action-icon js-edit-faculty')
            .attr('title', 'Редагувати факультет')
            .attr('data-facultyid', faculty.id);

        addActionCell.append(addActionIcon, editFaculty);

        row.append(addActionCell);

        row.addClass(($('#faculties-table tr').length + 1) % 2 === 0 ? 'row-gray' : 'row-beige');

        tbody.append(row);
    }
}

const addCourse = function(e) {
    $('#addCourseModal').attr('data-facultyid', $(e.target).data('facultyid'));
    showModal('addCourseModal');
}

const saveCourse = function(e) {
    const facultyId =  $('#addCourseModal').data('facultyid');
    $.ajax({
        url: '/api/university/' + universityId + '/courses/create',
        method: 'POST',
        data: {
            faculty_id: facultyId,
            course: $('#addCourseModal').find('.js-course-number').val(),
            _token: $(e.target).data('token'),
        },
        success: function (response) {
            const row = $(`#faculties-table tbody tr[data-facultyid="${facultyId}"]`);
            row.find('.js-list-courses').append(`<li class="list-course-item js-view-course" data-id="`+ response.data.id + `">`+ response.data.course + ' курс' +`</li>`);
            hideModal('addCourseModal');
        },
        error: function (xhr, status, error) {
            console.error('Помилка:', error);
        }
    });
};

const addGroup = function(e) {
    const inputField = `<input type="text" class="form-control js-group-title">`;
    $(inputField).insertBefore('.js-add-group');
    $(e.target).addClass('hidden');
    $('.js-save-group').removeClass('hidden');
}

const saveGroup = function(e) {
    $.ajax({
        url: '/api/university/' + universityId + '/groups/create',
        method: 'POST',
        data: {
            course_id: $('#courseInfo').data('courseid'),
            title: $('.js-groups-info').find('.js-group-title').val(),
            _token: $(e.target).data('token'),
        },
        success: function (response) {
            $('.js-groups').append(
                `<div class="js-group-item" data-groupid=` + response.data.id +`>` +
                response.data.title +
                `<i class="fa fa-eye js-view-group"></i>
                </div>`
            );

            $(e.target).addClass('hidden');
            $('.js-groups-info').find('input.js-group-title').remove();
            $('.js-add-group').removeClass('hidden');
        },
        error: function (xhr, status, error) {
            console.error('Помилка:', error);
        }
    });
}

const getCourseGroups = function(e) {
    const courseId = $(e.target).data('id');
    const facultyId = $(e.target).closest('tr').data('facultyid');

    searchGroups({ courseId: courseId }, 'courseInfo', drawCourseInfo);

    $('#courseInfo').attr('data-facultyid', facultyId).attr('data-courseid', courseId);

    showModal('courseInfo');
}

const drawCourseInfo = function (groups) {
    const courseInfoModalContent = $('#courseInfo .js-groups-info');
    courseInfoModalContent.find('.js-groups').empty();

    if (groups.length === 0) {
        courseInfoModalContent.find('.js-groups').text('Ще немає груп');
    } else {
        groups.forEach(function (group) {
            courseInfoModalContent.find('.js-groups').append(
                `<div class="js-group-item" data-groupid=` + group.id + `>` +
                group.title +
                `<i class="fa fa-eye js-view-group"></i>
                        </div>`);
        });
    }
}

const getGroupStudents = function (e) {
    const groupId = $(e.target).closest('.js-group-item').data('groupid');
    const courseId = $('#courseInfo').data('courseid');
    const facultyId = $('#courseInfo').data('facultyid');

    $.ajax({
        url: '/api/university/' + universityId + '/students?faculty=' + facultyId +'&courseId=' + courseId +'&groupId=' + groupId,
        method: 'GET',
        success: function (response) {
            const modal =  $('#courseInfo');
            modal.attr('data-groupid', groupId);
            modal.find('.js-students-content').empty();
            if (response.data.length === 0) {
                modal.find('.js-students-content').append(`<p>Ще немає студентів</p>`);
            } else {
                response.data.forEach(function (student) {
                    modal.find('.js-students-content').append(`<p>`+ student.user.full_name +`</p>`);
                });
            }
            modal.find('.js-group-info').removeClass('hidden');
        },
        error: function (xhr, status, error) {
            console.error('Помилка:', error);
        }
    });
}

