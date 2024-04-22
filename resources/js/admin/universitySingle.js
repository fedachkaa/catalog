import { hideSpinner, showSpinner} from "../general";

document.addEventListener('DOMContentLoaded', function () {
    $(document).on('click', '.js-approve-university', updateUniversity);
    $(document).on('click', '.js-reject-university', updateUniversity);
});

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
