const { toggleTabsSideBar } = require('./../general.js');
const { searchStudents, getStudents } = require('../common/students.js');

document.addEventListener('DOMContentLoaded', function () {
    toggleTabsSideBar('js-students');
    getStudents('teacherId=' + teacherId);

    $(document).on('click', '.js-search-students', searchStudents);
})
