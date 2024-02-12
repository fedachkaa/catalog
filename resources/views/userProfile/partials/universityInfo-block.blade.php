<div class="pl-52">
    <div class="js-university-name text-3xl"></div>
    <div class="js-university-acc-level text-3xl"></div>
    <div class="js-university-founded-at text-3xl"></div>

    @include('general.addressInfo--block')

    @include('general.contactInfo--block')

    <div class="faculties-block">
        <div>Faculty 1</div>
        <div>Faculty 1</div>
        <div>Faculty 1</div>
        <div>Faculty 1</div>
        <button class="save-btn js-add-faculty">Додати факультет</button>
        <button class="save-btn js-save-faculty hidden">Зберегти</button>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        $('.js-add-faculty').on('click', function () {
            const token = '<?= csrf_token(); ?>';
            const inputField = `
                <input type="text" class="form_field js-faculty-title">
                <input type="hidden" name="_token" value="`+ token + `" />
            `;
            $(inputField).insertBefore('.js-add-faculty');
            $(this).addClass('hidden');
            $('.js-save-faculty').removeClass('hidden');
        });

        $('.js-save-faculty').on('click', function () {
            $.ajax({
                url: '/profile/api/university/faculty/create',
                method: 'POST',
                data: {
                    university_id: $('.js-university-info').data('universityid'),
                    title: $('.js-faculty-title').val(),
                    _token: $('.faculties-block input[name="_token"]').val()
                },
                success: function (response) {
                    console.log('Успішно!', response);
                },
                error: function (xhr, status, error) {
                    console.error('Помилка:', error);
                }
            });

        });
    });

</script>
