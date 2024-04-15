<?php
/**
 * @var array $user
 */
?>

@include('userProfile.teacherProfile.partials.sidebar-template')

@include('general.password--block', ['userData' => $user])

<script>
    const universityId = <?= $user['university']['id'] ?? ''; ?>
</script>
