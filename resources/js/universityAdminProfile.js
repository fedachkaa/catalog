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
    const facultiesBlock = $('.js-faculties-block');
    facultiesBlock.attr('data-universityId', universityId);
    $('.js-faculties-list').empty();
    data.faculties.forEach(function (faculty) {
        const facultyItem = `<div class="faculty-list-item" data-id="` + faculty.id + `">`
            + faculty.title +
            `<i class="fa fa-eye js-view-faculty"></i>
            <i class="fa fa-trash"></i>
            </div>`;
        $('.js-faculties-list').append(facultyItem);
    });

    $('.js-view-faculty').on('click', function () {
        getFacultyInfo($(this).parent().data('id'));
    });

    toggleContentBlock('js-university-profile', 'admin-profile-content-block', 'js-faculties-block');
}

const getFacultyInfo = function(facultyId) {
    $.ajax({
        url: '/api/university/' + universityId +'/faculty/' + facultyId,
        method: 'GET',
        success: function (response) {
            const facultyItem = $('.js-faculty-item');

            facultyItem.attr('data-facultyid', response.data.id);
            facultyItem.find('.js-title').text(response.data.title);

            if (response.data.courses.length === 0) {
                facultyItem.find('.js-courses').text('Ще немає курсів');
            } else {
                $('.js-courses').empty();
                response.data.courses.forEach(function (course) {
                    facultyItem.find('.js-courses').append(
                        `<div class="js-course-item" data-courseid=` + course.id +`>`  +
                            course.course +
                            ` курс` +
                            `<i class="fa fa-eye js-view-course"></i>
                        </div>`);
                });
            }
            facultyItem.find('.js-faculty-info').removeClass('hidden');
        },
        error: function (xhr, status, error) {
            console.error('Помилка:', error);
        }
    });
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
            const facultyItem = `<div class="faculty-list-item" data-id="` + response.data.id + `">`
                + response.data.title +
                `<i class="fa fa-eye js-view-faculty"></i>
             </div>`;

            $('.js-faculties-list').append(facultyItem);

            $('.js-view-faculty').on('click', function () {
                getFacultyInfo($(this).parent().data('id'));
            });


            $('.js-faculties-container').find('input.js-faculty-title').addClass('hidden');
            $('.js-save-faculty').addClass('hidden');
            $('.js-add-faculty').removeClass('hidden');
        },
        error: function (xhr, status, error) {
            console.error('Помилка:', error);
        }
    });
}

const addCourse = function(e) {
    const inputField = `<input type="number" class="form-control js-course-number" min="1" max="6">`;
    $(inputField).insertBefore('.js-add-course');
    $(e.target).addClass('hidden');
    $('.js-save-course').removeClass('hidden');
}

const saveCourse = function(e) {
    const facultyItem = $(e.target).closest('.js-faculty-item');

    $.ajax({
        url: '/api/university/' + universityId +'/faculty/' + facultyItem.data('facultyid') + '/course/create',
        method: 'POST',
        data: {
            course: facultyItem.find('.js-course-number').val(),
            _token: $(e.target).data('token'),
        },
        success: function (response) {
            $('.js-courses').append(
                `<div class="js-course-item" data-courseid=` + response.data.id +`>` +
                    response.data.course +
                    ` курс` +
                    `<i class="fa fa-eye js-view-course"></i>
                </div>`);
            $(e.target).addClass('hidden');
            $('.js-course-number').remove();
            $('.js-add-course').removeClass('hidden');
        },
        error: function (xhr, status, error) {
            console.error('Помилка:', error);
        }
    });
};

const getCourseGroups = function(e) {
    const courseId = $(e.target).closest('.js-course-item').data('courseid');

    $.ajax({
        url: '/api/university/'+ universityId +'/faculty/' + $('.js-faculty-item').data('facultyid') + '/course/' + courseId +'/groups',
        method: 'GET',
        success: function (response) {
            const groupsContainer = $('.js-faculty-item .js-groups-info');
            groupsContainer.attr('data-courseid', courseId);
            if (response.data.length === 0) {
                groupsContainer.find('.js-groups').text('Ще немає груп');
            } else {
                response.data.forEach(function (group) {
                    groupsContainer.find('.js-groups').append(
                        `<div class="js-group-item" data-groupid=` + group.id +`>`  +
                            group.title +
                            `<i class="fa fa-eye js-view-group"></i>
                        </div>`);
                });
            }
            groupsContainer.removeClass('hidden');
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
    const courseId = $(e.target).closest('.js-group-item').parent().parent().data('courseid');
    const facultyId = $(e.target).closest('.js-group-item').parent().parent().parent().data('facultyid');

    $.ajax({
        url: '/api/university/' + universityId + '/faculty/' + facultyId +'/course/' + courseId +'/group/' + groupId + '/students',
        method: 'GET',
        success: function (response) {
            const modal =  $('#groupStudents');
            modal.find('.modal-title').text('Студенти ' + $(e.target).closest('.js-group-item').text());
            modal.attr('data-groupid', groupId);
            modal.attr('data-courseid', courseId);
            modal.attr('data-facultyid', facultyId);

            modal.find('.js-students-content').empty();
            if (response.data.length === 0) {
                modal.find('.js-students-content').append(`<p>Ще немає студентів</p>`);
            } else {
                response.data.forEach(function (student) {
                    modal.find('.js-students-content').append(`<p>`+ student.user.full_name +`</p>`);
                });
            }
            showModal('groupStudents');
        },
        error: function (xhr, status, error) {
            console.error('Помилка:', error);
        }
    });
}

const showModal = function (id) {
    var modal = document.getElementById(id);
    modal.style.display = "block";

    var closeBtn = document.getElementsByClassName("close")[0];

    closeBtn.onclick = function() {
        modal.style.display = "none";
    }
    window.onclick = function(event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    }
}

const addStudent = function (e) {
    const inputsField = `<div class="js-form-fields">
        <input type="text" class="form-control js-first-name" placeholder="Введіть ім'я">
        <input type="text" class="form-control js-last-name" placeholder="Введіть прізвище">
        <input type="email" class="form-control js-email" placeholder="Введіть електронну пошту">
        <input type="text" class="form-control js-phone-number" placeholder="Введіть номер телефону">
    </div>`;
    $(inputsField).insertBefore('.js-add-student');
    $(e.target).addClass('hidden');
    $('.js-save-student').removeClass('hidden');
};

const saveStudent = function (e) {
    const modal = $('#groupStudents');

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
            $('.js-add-student').removeClass('hidden');
        },
        error: function (xhr, status, error) {
            console.error('Помилка:', error);
        }
    });

}
module.exports = {
    getUniversity,
    getFaculties,
    displayUniversityData,
    displayFacultiesData,
    getFacultyInfo,
}
