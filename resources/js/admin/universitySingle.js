import {getUserBaseInfo, hideSpinner, showSpinner} from "../general.js";

document.addEventListener('DOMContentLoaded', function () {
    $(document).on('click', '.js-approve-university', updateUniversity);
    $(document).on('click', '.js-reject-university', updateUniversity);
    $(document).on('click', '.js-show-user-info', showUserInfo);

    initUniversityEntities();
    $('#faculties-table, #catalog-table, #teachers-table, #students-table').DataTable({
        autoWidth: false,
        language: {
            lengthMenu: '_MENU_ записів',
            search: 'Шукати',
            info: 'Показано з _START_ по _END_ з _TOTAL_ записів'
        }
    });
});

const showUserInfo = function (e) {
    getUserBaseInfo($(e.target).closest('tr').data('userid'));
}

const initUniversityEntities = function () {
    $('.js-accordion-title').on('click', function () {
        $(this).siblings('.js-accordion-body').toggleClass('hidden');
    });
}
const updateUniversity = function (e) {
    const universityId = $(document).find('.js-university-single').data('universityid');
    showSpinner();

    $.ajax({
        url: '/admin/api/university/' + universityId,
        method: 'PUT',
        data: {
            'is_active': $(e.target).data('approved'),
            '_token': $(e.target).parent().data('token'),
        },
        success: function () {
            window.location.reload();
        },
        error: function (response) {
            hideSpinner();
            console.log(response);
        }
    });
}
