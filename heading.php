<nav class="navbar navbar-expand navbar-light navbar-bg">
  <a class="sidebar-toggle js-sidebar-toggle">
    <i class="hamburger align-self-center"></i>
  </a>

  <div class="navbar-collapse collapse">
    <ul class="navbar-nav navbar-align">
      <li class="nav-item dropdown">
        <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
          <i class="align-middle" data-feather="settings"></i>
        </a>

        <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
          <img src="<?= $user["Client_Logo"] ?>" class="avatar img-fluid rounded me-1" alt="" /> <span class="text-dark"><?= $user["Client_FullName"] ?></span>
        </a>

        <div class="dropdown-menu dropdown-menu-end me-2 mt-2 m-sm-2">
          <a class="dropdown-item" href="client_edit.php?client_id=<?= $user["Client_Id"] ?>"><i class="align-middle me-1" data-feather="user"></i> Profile</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="logout.php"><i class="align-middle me-1" data-feather="log-out"></i>Log out</a>
        </div>

      </li>
    </ul>
  </div>
</nav>
