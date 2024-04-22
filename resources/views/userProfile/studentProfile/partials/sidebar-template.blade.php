<?php
/**
 * @var array $user
 */
?>

<div class="js-student-profile">
    <div id="nav">
        <ul class="sidebar_nav">
            <li class="sidebar-menu-title active-tab js-user-profile">
                <a href="/profile">
                    <i class="fas fa-user"></i>
                    <span>Особисті дані</span>
                </a>
            </li>
            <li class="sidebar-menu-title js-catalogs">
                <a href="/university/<?= $user['university']['id']; ?>/catalogs">
                    <i class="fa-solid fa-book-bookmark"></i>
                    <span>Каталоги робіт</span>
                </a>
            </li>
            <li class="sidebar-menu-title js-topic-requests">
                <a href="/topic-requests">
                    <i class="fas fa-square-check"></i>
                    <span>Мої запити</span>
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
    const universityId = <?= $user['university']['id'] ?? ''; ?>;
    const studentId = <?= $user['id'] ?? ''; ?>;
</script>
