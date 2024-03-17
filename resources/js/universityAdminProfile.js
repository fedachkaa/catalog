require('./bootstrap');
window.$ = window.jQuery = require('jquery');

const { toggleTabsSideBar, toggleContentBlock } = require('./general.js');

document.addEventListener("DOMContentLoaded", function () {
    $('.js-university').on('click', function () {
        toggleTabsSideBar('js-university');
        getUniversity();
    });

    $('.js-faculties').on('click', function () {
        toggleTabsSideBar('js-faculties');
        getFaculties();
    });

    $('.js-teachers').on('click', function () {
        toggleTabsSideBar('js-teachers');
        getTeachers();
    });

    $('.js-students').on('click', function () {
        toggleTabsSideBar('js-students');
        getStudents();
    });

    $(document).on('click', '.js-add-faculty', addFaculty);
    $(document).on('click', '.js-save-faculty', saveFaculty);

    $(document).on('click', '.js-add-course', addCourse);
    $(document).on('click', '.js-save-course', saveCourse);
    $(document).on('click', '.js-view-course', getCourseGroups);

    $(document).on('click', '.js-add-group', addGroup);
    $(document).on('click', '.js-save-group', saveGroup);
    $(document).on('click', '.js-view-group', getGroupStudents);

    $(document).on('click', '.js-add-student', addStudent);
    $(document).on('click', '.js-save-student', saveStudent);
    $(document).on('click', '.js-import-students', importStudents);
    $(document).on('click', '.js-import-students-save', importStudentsStore);

    $(document).on('click', '.js-add-teacher', addTeacher);
    $(document).on('click', '.js-save-teacher', saveTeacher);

    $(document).on('click', '.js-search-students', searchStudents)

});

const getUniversity = function() {
    $.ajax({
        url: '/profile/api/university',
        method: 'GET',
        success: function (response) {
            displayUniversityData(response.data);
        },
        error: function (xhr, status, error) {
            console.error('Помилка:', error);
        }
    });
}

const getFaculties = function() {
    $.ajax({
        url: '/api/university/' + universityId +'/faculties',
        method: 'GET',
        success: function (response) {
            displayFacultiesData(response.data);
        },
        error: function (xhr, status, error) {
            console.error('Помилка:', error);
        }
    });
}

const displayUniversityData = function(data) {
    const universityBlock = $('.js-university-info');

    universityBlock.data('universityid', data.id);
    universityBlock.find('.js-university-name').text(data.name);
    universityBlock.find('.js-city').text(data.city);
    universityBlock.find('.js-address').text(data.address);
    universityBlock.find('.js-phone').text(data.phone_number);
    universityBlock.find('.js-email').text(data.email);
    universityBlock.find('.js-website').text(data.website);
    universityBlock.find('.js-university-acc-level').text(data.accreditation_level);
    universityBlock.find('.js-university-founded').text(data.founded_at);

    toggleContentBlock('js-university-profile', 'admin-profile-content-block', 'js-university-info');
}

const displayFacultiesData = function(data) {
    const tbody = $('#faculties-table tbody');
    tbody.empty();

    data.faculties.forEach((faculty, id) => {
        drawSingleFaculty(faculty);
    });
    toggleContentBlock('js-university-profile', 'admin-profile-content-block', 'js-faculties-block');
}

const addFaculty = function(e) {
    const inputField = `<input type="text" class="form-control js-faculty-title">`;
    $(inputField).insertBefore('.js-add-faculty');
    $(e.target).addClass('hidden');
    $('.js-save-faculty').removeClass('hidden');
}

const saveFaculty = function(e) {
    $.ajax({
        url: 'api/university/'+ universityId +'/faculty/create',
        method: 'POST',
        data: {
            university_id: $('.js-faculties-block').data('universityid'),
            title: $('.js-faculty-title').val(),
            _token: $(e.target).data('token'),
        },
        success: function (response) {
            drawSingleFaculty(response.data);
            $('.js-save-faculty').addClass('hidden');
            $('.js-add-faculty').removeClass('hidden');
        },
        error: function (xhr, status, error) {
            console.error('Помилка:', error);
        }
    });
}

const addCourse = function(e) {
    $('#addCourseModal').attr('data-facultyid', $(e.target).data('facultyid'));
    showModal('addCourseModal');
}

const saveCourse = function(e) {
    const facultyId = $('#addCourseModal').data('facultyid');
    $.ajax({
        url: '/api/university/' + universityId +'/faculty/' + facultyId + '/course/create',
        method: 'POST',
        data: {
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

const getCourseGroups = function(e) {
    const courseId = $(e.target).data('id');
    const facultyId = $(e.target).closest('tr').data('facultyid');
    $.ajax({
        url: '/api/university/'+ universityId +'/faculty/' + facultyId + '/course/' + courseId +'/groups',
        method: 'GET',
        success: function (response) {
            $('#courseInfo')
                .attr('data-facultyid', facultyId)
                .attr('data-courseid', courseId);
            const courseInfoModalContent = $('#courseInfo .js-groups-info');
            courseInfoModalContent.find('.js-groups').empty();

            if (response.data.length === 0) {
                courseInfoModalContent.find('.js-groups').text('Ще немає груп');
            } else {
                response.data.forEach(function (group) {
                    courseInfoModalContent.find('.js-groups').append(
                        `<div class="js-group-item" data-groupid=` + group.id +`>`  +
                            group.title +
                            `<i class="fa fa-eye js-view-group"></i>
                        </div>`);
                });
            }
            showModal('courseInfo');
        },
        error: function (xhr, status, error) {
            console.error('Помилка:', error);
        }
    });
}

const addGroup = function(e) {
    const inputField = `<input type="text" class="form-control js-group-title">`;
    $(inputField).insertBefore('.js-add-group');
    $(e.target).addClass('hidden');
    $('.js-save-group').removeClass('hidden');
}

const saveGroup = function(e) {
    const courseId = $(e.target).closest('.js-groups-info').data('courseid');
    const facultyId = $(e.target).closest('.js-faculty-item').data('facultyid');

    $.ajax({
        url: '/api/university/' + universityId + '/faculty/' + facultyId +'/course/' + courseId + '/group/create',
        method: 'POST',
        data: {
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

const getGroupStudents = function (e) {
    const groupId = $(e.target).closest('.js-group-item').data('groupid');
    const courseId = $('#courseInfo').data('courseid');
    const facultyId = $('#courseInfo').data('facultyid');

    $.ajax({
        url: '/api/university/' + universityId + '/faculty/' + facultyId +'/course/' + courseId +'/group/' + groupId + '/students',
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

const showModal = function (id) {
    var modal = $('#' + id);
    modal.css('display', 'block');

    var closeBtn = modal.find('.close');

    closeBtn.on('click', function() {
        modal.css('display', 'none');
    });

    $(window).on('click', function(event) {
        if (event.target === modal[0]) {
            modal.css('display', 'none');
        }
    });
}

const hideModal = function (id) {
    var modal = $('#' + id);
    modal.css('display', 'none');
}

const addStudent = function (e) {
    const inputsField = `<div class="js-form-fields">
        <input type="text" class="form-control js-first-name" placeholder="Введіть ім'я">
        <input type="text" class="form-control js-last-name" placeholder="Введіть прізвище">
        <input type="email" class="form-control js-email" placeholder="Введіть електронну пошту">
        <input type="text" class="form-control js-phone-number" placeholder="Введіть номер телефону">
    </div>`;
    $(inputsField).insertBefore($('#courseInfo').find('.js-new-student-form .js-save-student'));
    // $(e.target).addClass('hidden'); add disable class
    $('.js-save-student').removeClass('hidden');
};

const saveStudent = function (e) {
    const modal = $('#courseInfo');

    $.ajax({
        url: '/api/university/' + universityId + '/faculty/' + modal.data('facultyid') +'/course/' + modal.data('courseid') +'/group/' + modal.data('groupid') + '/students',
        method: 'POST',
        data: {
            first_name: modal.find('.js-first-name').val(),
            last_name: modal.find('.js-last-name').val(),
            email: modal.find('.js-email').val(),
            phone_number: modal.find('.js-phone-number').val(),
            _token: $(e.target).data('token'),
        },
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

const getTeachers = function (e) {
    $.ajax({
        url: '/api/university/' + universityId +'/teachers',
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
        row.append($('<td>').text('Subjects ...'));
        const actionsCell = $('<td>');
        const editIcon = $('<i>').addClass('fas fa-edit action-icon');
        const deleteIcon = $('<i>').addClass('fas fa-trash action-icon');
        actionsCell.append(editIcon, deleteIcon);
        row.append(actionsCell);

        row.addClass(id % 2 === 0 ? 'row-gray' : 'row-beige');
        tbody.append(row);
    });
    toggleContentBlock('js-university-profile', 'admin-profile-content-block', 'js-teachers-block');
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

const drawSingleFaculty = function (faculty) {
    const tbody = $('#faculties-table tbody');
    const row = $(`<tr data-facultyid=` + faculty.id +`>`);

    row.append($('<td>').text(faculty.id));
    row.append($('<td>').text(faculty.title));

    const coursesList = $('<ul class="js-list-courses">');
    faculty.courses.forEach(course => {
        const listItem = $(`<li class="list-course-item js-view-course" data-id="` + course.id +`">`).text(course.course + ' курс');
        coursesList.append(listItem);
    });

    row.append($('<td>').append(coursesList));

    const addActionCell = $('<td>');
    const addActionIcon = $('<i>').addClass('fas fa-plus action-icon js-add-course')
        .attr('title', 'Додати курс')
        .attr('data-facultyid', faculty.id);
    addActionCell.append(addActionIcon);
    row.append(addActionCell);

    row.addClass(($('#faculties-table tr').length + 1) % 2 === 0 ? 'row-gray' : 'row-beige');

    tbody.append(row);
}

const getStudents = function (query = '') {
    $.ajax({
        url: '/api/university/' + universityId +'/students?' + query,
        method: 'GET',
        success: function (response) {
            console.log(response);
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

    data.forEach((student, id) => {
        const row = $('<tr>');
        row.append($('<td>').text(student.user_id));
        row.append($('<td>').text(student.user.full_name));
        row.append($('<td>').text(student.faculty.title));
        row.append($('<td>').text(student.course.course + ' курс'));
        row.append($('<td>').text(student.group.title));
        row.addClass(id % 2 === 0 ? 'row-gray' : 'row-beige');
        tbody.append(row);
    });
    toggleContentBlock('js-university-profile', 'admin-profile-content-block', 'js-students-block');
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
module.exports = {
    getUniversity,
    getFaculties,
    displayUniversityData,
    displayFacultiesData,
}
