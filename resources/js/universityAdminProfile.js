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

    $('.js-add-faculty').on('click', function () {
        addFaculty();
    });

    $('.js-save-faculty').on('click', function () {
        saveFaculty();
    });

    $('.js-add-course').on('click', addCourse);

    $('.js-save-course').on('click', saveCourse);

    $(document).on('click', '.js-view-course', getCourseGroups);

    $(document).on('click', '.js-add-group', addGroup);
    $(document).on('click', '.js-save-group', saveGroup);

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
        url: '/profile/api/faculties',
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
    facultiesBlock.attr('data-universityId', data.university_id);
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
        url: '/profile/api/faculty/' + facultyId,
        method: 'GET',
        success: function (response) {
            const facultyItem = $('.js-faculty-item');

            facultyItem.attr('data-facultyid', response.data.id);
            facultyItem.find('.js-title').text(response.data.title);
            
            if (response.data.courses.length === 0) {
                facultyItem.find('.js-courses').text('Ще немає курсів');
            } else {
                response.data.courses.forEach(function (course) {
                    facultyItem.find('.js-courses').append(
                        `<div class="js-course-item" data-courseid=` + response.data.id +`>`  + 
                            course.course + 
                            ` курс` + 
                            `<i class="fa fa-eye js-view-course"></i>
                            <i class="fa fa-trash"></i>
                        </div>`);
                });
            }
            facultyItem.find('.js-faculty-info').removeClass('hidden');
            console.log('Success:', response);
        },
        error: function (xhr, status, error) {
            console.error('Помилка:', error);
        }
    });
}

const addFaculty = function() {
    const token = '<?= csrf_token(); ?>';
    const inputField = `
        <input type="text" class="form-control js-faculty-title">
        <input type="hidden" name="_token" value="`+ token + `" />
    `;
    $(inputField).insertBefore('.js-add-faculty');
    $(this).addClass('hidden');
    $('.js-save-faculty').removeClass('hidden');
}

const saveFaculty = function() {
    $.ajax({
        url: '/profile/api/faculty/create',
        method: 'POST',
        data: {
            university_id: $('.js-faculties-block').data('universityid'),
            title: $('.js-faculty-title').val(),
            _token: $('.faculties-block input[name="_token"]').val()
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
        url: '/profile/api/course/create',
        method: 'POST',
        data: {
            faculty_id: facultyItem.data('facultyid'),
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
        url: '/profile/api/course/' + courseId + '/groups',
        method: 'GET',
        success: function (response) {
            const groupsContainer = $('.js-faculty-item .js-groups-info');  
            groupsContainer.attr('data-courseid', courseId);          
            if (response.data.length === 0) {
                groupsContainer.find('.js-groups').text('Ще немає груп');
            } else {
                response.data.forEach(function (group) {
                    groupsContainer.find('.js-groups').append(
                        `<div class="js-group-item" data-group=` + group.id +`>`  + 
                            group.title + 
                            `<i class="fa fa-eye js-view-course"></i>
                        </div>`);
                });
            }
            groupsContainer.removeClass('hidden');
            console.log('Success:', response);
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

    $.ajax({
        url: '/profile/api/course/' + courseId +'/group/create',
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

module.exports = {
    getUniversity,
    getFaculties,
    displayUniversityData,
    displayFacultiesData,
    getFacultyInfo,
    addFaculty,
    saveFaculty,
}
