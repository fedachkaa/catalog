<?php
/**
 * @var array $user
 */
?>

<div class="js-university-profile">
    <div id="nav">
        <ul class="sidebar_nav">
            <li class="sidebar-menu-title active-tab js-user-profile">
                <a href="/profile">
                    <i class="fas fa-user"></i>
                    <span>Особисті дані</span>
                </a>
            </li>
            <li class="sidebar-menu-title js-university">
                <a href="/university/{{ $user['university']['id'] }}">
                    <i class="fa-solid fa-building-columns"></i>
                    <span>Університет</span>
                </a>
            </li>
            <li class="sidebar-menu-title js-faculties">
                <a href="/university/{{ $user['university']['id'] }}/faculties">
                    <i class="fa-solid fa-building-columns"></i>
                    <span>Факультети</span>
                </a>
            </li>
            <li class="sidebar-menu-title js-teachers">
                <a href="/university/{{ $user['university']['id'] }}/teachers">
                    <i class="fa-solid fa-user-tie"></i>
                    <span>Викладачі</span>
                </a>
            </li>
            <li class="sidebar-menu-title js-students">
                <a href="/university/{{ $user['university']['id'] }}/students">
                    <i class="fa-solid fa-user-graduate"></i>
                    <span>Студенти</span>
                </a>
            </li>
            <li class="sidebar-menu-title js-subjects">
                <a href="/university/{{ $user['university']['id'] }}/subjects">
                    <i class="fa-solid fa-book-open"></i>
                    <span>Предмети</span>
                </a>
            </li>
            <li class="sidebar-menu-title js-catalogs">
                <a href="/university/{{ $user['university']['id'] }}/catalogs">
                    <i class="fa-solid fa-book-bookmark"></i>
                    <span>Каталоги робіт</span>
                </a>
            </li>
            <li class="sidebar-menu-title js-catalogs">
                <a href="/university/{{ $user['university']['id'] }}/topics">
                    <i class="fa-solid fa-book-bookmark"></i>
                    <span>Теми</span>
                </a>
            </li>
            <li class="sidebar-menu-title">
                <a href="{{ route('logout') }}">
                    <span>Вийти</span>
                    <i class="fa-solid fa-right-from-bracket"></i>
                </a>
            </li>
        </ul>
    </div>
</div>

<script>
    const universityId = <?= $user['university']['id'] ?? ''; ?>
</script>
