const { showSpinner, hideSpinner} = require("../general");

const searchFaculties = function (block) {
    showSpinner();

    $.ajax({
        url: '/api/university/' + universityId + '/faculties',
        method: 'GET',
        success: function (response) {
            const facultySelect = $('#' + block).find('.js-faculty');
            facultySelect.empty();
            facultySelect.append($('<option>').attr('value', '').text('Виберіть факультет'));

            response.data.faculties.forEach(faculty => {
                facultySelect.append($('<option>').attr('value', faculty.id).text(faculty.title));
            });

            hideSpinner();
        },
        error: function (xhr, status, error) {
            console.error('Помилка:', error);
            hideSpinner();
        }
    });
}

const searchGroups = function (searchParams, block, callback = () => {}) {
    showSpinner();

    let queryString = '';

    for (const key in searchParams) {
        if (searchParams.hasOwnProperty(key)) {
            const value = searchParams[key];
            queryString += encodeURIComponent(key) + '=' + encodeURIComponent(value) + '&';
        }
    }

    $.ajax({
        url: '/api/university/' + universityId + '/groups?' + queryString,
        method: 'GET',
        success: function (response) {
            callback(response.data, block);
            hideSpinner();
        },
        error: function (xhr, status, error) {
            console.error('Помилка:', error);
            hideSpinner();
        }
    });
}

const searchCourses = function (facultyId, block) {
    showSpinner();

    $.ajax({
        url: '/api/university/' + universityId + '/courses?facultyId=' + facultyId,
        method: 'GET',
        success: function (response) {
            const coursesSelect = $('#' + block).find('.js-course');
            coursesSelect.empty();
            coursesSelect.append($('<option>').attr('value', '').text('Виберіть курс'));

            response.data.forEach(course => {
                coursesSelect.append($('<option>').attr('value', course.id).text(course.course + ' курс'));
            });

            hideSpinner();
        },
        error: function (xhr, status, error) {
            console.error('Помилка:', error);
            hideSpinner();
        }
    });
}

const searchTeachers = function (block, searchParams = {}, callback = () => {}) {
    showSpinner();

    let queryString = '';

    for (const key in searchParams) {
        if (searchParams.hasOwnProperty(key)) {
            const value = searchParams[key];
            queryString += encodeURIComponent(key) + '=' + encodeURIComponent(value) + '&';
        }
    }

    $.ajax({
        url: '/api/university/' + universityId +'/teachers?' + queryString,
        method: 'GET',
        success: function (response) {
            const teachersSelect = $(block).find('.js-teachers-select');
            teachersSelect.empty();
            teachersSelect.append($('<option >').attr('value', '').text('Виберіть викладача'));

            response.data.teachers.forEach(teacher => {
                teachersSelect.append($('<option class="js-teacher-item">').attr('value', teacher.user_id).text(teacher.user.full_name));
            });

            initTeachersSelectClick(block, teachersSelect);
            initRemoveTeacherClick(block);
            teachersSelect.removeClass('hidden');
            hideSpinner();
            callback();
        },
        error: function (xhr, status, error) {
            console.error('Помилка:', error);
            hideSpinner();
        }
    });
}

const initTeachersSelectClick = function (block, teachersSelect) {
    teachersSelect.on('change', function() {
        const selectedTeacherId = $(this).val();
        const selectedTeacherName = $(this).find('option:selected').text();

        const teachersList = $(block + ' .js-teachers-list ul');
        const listItem = $(`<li data-teacherid="` + selectedTeacherId + `">`).text(selectedTeacherName);
        const deleteIcon = $('<i>').addClass('fas fa-times js-delete-teacher');
        listItem.append(deleteIcon);
        teachersList.append(listItem);

        $(this).find('option:selected').hide();
        $(this).val('');
    });
}

const initRemoveTeacherClick = function (block) {
    $(block + ' .js-teachers-list').on('click', '.js-delete-teacher', function() {
        const teacherId = parseInt($(this).parent().data('teacherid'), 10);
        $(block + ' .js-teachers-select').find('option').each(function() {
            if (parseInt($(this).val(), 10) === teacherId) {
                $(this).show();
            }
        });
        $(this).parent().remove();
    });
}


module.exports = {
    searchGroups,
    searchFaculties,
    searchCourses,
    searchTeachers,
    initRemoveTeacherClick
};
