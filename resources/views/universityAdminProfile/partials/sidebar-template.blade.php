<?php
/**
 * @var array $user
 */
?>

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
            <li class="sidebar-menu-title js-teachers">
                <a href="/profile/university/<?= $user['university']['id']; ?>/teachers">
                    <i class="fa-solid fa-user-tie"></i>
                    <span>Викладачі</span>
                </a>
            </li>
            <li class="sidebar-menu-title js-students">
                <a>
                    <i class="fa-solid fa-user-graduate"></i>
                    <span>Студенти</span>
                </a>
            </li>
            <li class="sidebar-menu-title js-subjects">
                <a href="/university/<?= $user['university']['id']; ?>/subjects">
                    <i class="fa-solid fa-book-open"></i>
                    <span>Предмети</span>
                </a>
            </li>
            <li class="sidebar-menu-title js-catalogs">
                <a href="/university/catalogs">
                    <i class="fa-solid fa-book-bookmark"></i>
                    <span>Каталоги робіт</span>
                </a>
            </li>
        </ul>
    </div>
</div>

<script>
    const universityId = <?= $user['university']['id'] ?? ''; ?>
</script>
