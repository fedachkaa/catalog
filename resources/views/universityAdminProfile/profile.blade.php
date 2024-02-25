<style>
    .modal {
        display: none; /* Початково вікно приховане */
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.5); /* Прозорий чорний колір */
    }

    .modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

</style>

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

    <div id="groupStudents" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 class="modal-title"></h2>
            <button class="save-btn js-add-student">Додати студента </button>
            <button class="save-btn js-save-student hidden" data-token="{{ csrf_token() }}">Зберегти</button>
            <div class="js-students-content">
            </div>
        </div>
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
