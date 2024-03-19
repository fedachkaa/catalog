<?php
/**
 * @var array $user
 */
?>


@include('universityAdminProfile.partials.sidebar-template')

@include('userProfile.partials.userInfo-block', ['userData' => $user])

{{--@include('universityAdminProfile.partials.add-student-modal')--}}

    {{--    <div class="js-user-info admin-profile-content-block">--}}
    {{--        @include('userProfile.partials.userInfo-block', ['userData' => $user])--}}
    {{--    </div>--}}

    {{--    <div class="js-university-info hidden admin-profile-content-block">--}}
    {{--        @include('universityAdminProfile.partials.universityInfo-block')--}}
    {{--    </div>--}}

    {{--    <div class="js-faculties-block hidden admin-profile-content-block">--}}
    {{--        @include('universityAdminProfile.partials.faculties-block')--}}
    {{--    </div>--}}
    {{--    @include('universityAdminProfile.partials.course-info-modal')--}}
    {{--    @include('universityAdminProfile.partials.add-course-modal')--}}


    {{--    <div class="js-students-block hidden admin-profile-content-block">--}}
    {{--        @include('universityAdminProfile.partials.students-block')--}}
    {{--    </div>--}}

<script>
    const universityId = <?= $user['university']['id'] ?? ''; ?>
</script>
