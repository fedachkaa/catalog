<div id="nav">
    <ul class="sidebar_nav">
        <li class="sidebar-menu-title active-tab">
            <a>
                <i class="fas fa-user"></i>
                <span>Особисті дані</span>
            </a>
        </li>
        <li class="sidebar-menu-title js-university">
            <a>
                <i class="fa-solid fa-building-columns"></i>
                <span>Університет</span>
            </a>
        </li>
        <li class="sidebar-menu-title">
            <a>
                <i class="fa-solid fa-user-tie"></i>
                <span>Викладачі</span>
            </a>
        </li>
        <li class="sidebar-menu-title">
            <a>
                <i class="fa-solid fa-user-graduate"></i>
                <span>Студенти</span>
            </a>
        </li>
    </ul>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
       $('.js-university').on('click', function () {
           $('.sidebar-menu-title').each(function () {
              if ($(this).hasClass('js-university')) {
                  $(this).addClass('active-tab');
              } else {
                  $(this).removeClass('active-tab');
              }
           });

           $.ajax({
               url: '/profile/api/university',
               method: 'GET',
               success: function (response) {
                   displayUniversityData(response.data);
                   // general.showResponse(response.status, response.message);
                   console.log('Успішно!', response);
               },
               error: function (xhr, status, error) {
                   // general.showResponse(response.status, response.message);
                   console.error('Помилка:', error);
               }
           });
       });
    });

    function displayUniversityData(data) {
        const universityBlock = $('.js-university-info');
        universityBlock.data('universityid', data.id);
        universityBlock.find('.js-university-name').text(data.name);
        universityBlock.find('.js-university-city').text(data.city);
        universityBlock.find('.js-university-address').text(data.address);
        universityBlock.find('.js-phone-number').text(data.phone_number);
        universityBlock.find('.js-email').text(data.email);
        universityBlock.find('.js-website').text(data.website);
        universityBlock.find('.js-university-acc-level').text(data.accreditation_level);
        universityBlock.find('.js-university-founded-at').text(data.founded_at);
        universityBlock.removeClass('hidden');
        $('.js-user-info').addClass('hidden');
    }
</script>
