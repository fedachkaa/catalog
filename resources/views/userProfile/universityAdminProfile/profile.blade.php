<?php
/**
 * @var array $user
 */
?>

@include('userProfile.universityAdminProfile.partials.sidebar-template')

@include('userProfile.common.userInfo-block', ['userData' => $user])
