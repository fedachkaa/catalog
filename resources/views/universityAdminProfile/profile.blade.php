<div class="js-university-profile">
    <div id="nav">
        <ul class="sidebar_nav">
            <li class="sidebar-menu-title active-tab js-user-profile">
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
            <li class="sidebar-menu-title js-faculties">
                <a>
                    <i class="fa-solid fa-building-columns"></i>
                    <span>Факультети</span>
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

    <div class="js-user-info admin-profile-content-block">
        @include('userProfile.partials.userInfo-block', ['userData' => $user])
    </div>

    <div class="js-university-info hidden admin-profile-content-block">
        @include('universityAdminProfile.partials.universityInfo-block')
    </div>

    <div class="js-faculties-block hidden admin-profile-content-block">
        @include('universityAdminProfile.partials.faculties-block')
    </div>
</div>