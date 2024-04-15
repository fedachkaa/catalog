<?php
/**
 * @var array $user
 */
?>

@include('teacherProfile.partials.sidebar-template')

@include('userProfile.partials.userInfo-block', ['userData' => $user])

<script>
    const universityId = <?= $user['university']['id'] ?? ''; ?>
</script>
