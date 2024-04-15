<?php
/**
 * @var array $user
 */
?>

<div class="js-teacher-profile">
    <div id="nav">
        <ul class="sidebar_nav">
            <li class="sidebar-menu-title active-tab js-user-profile">
                <a href="/profile">
                    <i class="fas fa-user"></i>
                    <span>Особисті дані</span>
                </a>
            </li>
            <li class="sidebar-menu-title js-subjects">
                <a href="/university/<?= $user['university']['id']; ?>/subjects">
                    <i class="fa-solid fa-book-open"></i>
                    <span>Мої предмети</span>
                </a>
            </li>
            <li class="sidebar-menu-title js-students">
                <a href="/university/<?= $user['university']['id']; ?>/students">
                    <i class="fa-solid fa-user-graduate"></i>
                    <span>Мої студенти</span>
                </a>
            </li>
            <li class="sidebar-menu-title js-catalogs">
                <a href="/university/<?= $user['university']['id']; ?>/catalogs">
                    <i class="fa-solid fa-book-bookmark"></i>
                    <span>Каталоги робіт</span>
                </a>
            </li>
        </ul>
    </div>
</div>

<script>
    const universityId = <?= $user['university']['id'] ?? ''; ?>;
    const teacherId = <?= $user['id'] ?? ''; ?>;
</script>
