<nav id="sidebar" class="sidebar js-sidebar">
  <div class="sidebar-content js-simplebar">
    <a class="sidebar-brand" href="index.php"><span class="align-middle">P.G.D</span></a>

    <ul class="sidebar-nav">
      <li class="sidebar-header">Pages</li>

      <li class="sidebar-item active">
        <a class="sidebar-link" href="dashboard.php">
          <i class="align-middle" data-feather="clipboard"></i><span class="align-middle">Dashboard</span>
        </a>
      </li>

      <li class="sidebar-item">
        <a class="sidebar-link" href="client_edit.php?client_id=<?= $user["Client_Id"] ?>">
          <i class="align-middle" data-feather="user"></i><span class="align-middle">Profile</span>
        </a>
      </li>

      <li class="sidebar-item">
        <a class="sidebar-link" href="logout.php">
          <i class="align-middle" data-feather="log-out"></i><span class="align-middle">Log Out</span>
        </a>
      </li>

      <li class="sidebar-header">
        <?php if ($user["Client_Type_Id"] == 1): ?>
        Set Up
        <?php else: ?>
        View
        <?php endif; ?>
      </li>

      <?php if ($user["Client_Type_Id"] == 1): ?>
      <li class="sidebar-item">
        <a class="sidebar-link" href="client.php">
          <i class="align-middle" data-feather="users"></i><span class="align-middle">Client Add and Edit</span>
        </a>
      </li>
      <?php endif; ?>

      <li class="sidebar-item">
        <a class="sidebar-link" href="client_category.php">
          <i class="align-middle" data-feather="user"></i><span class="align-middle">Client Category</span>
        </a>
      </li>

      <li class="sidebar-item">
        <a class="sidebar-link" href="governorate.php">
          <i class="align-middle" data-feather="globe"></i><span class="align-middle">Governorate</span>
        </a>
      </li>

      <li class="sidebar-item">
        <a class="sidebar-link" href="caza.php">
          <i class="align-middle" data-feather="globe"></i><span class="align-middle">Caza</span>
        </a>
      </li>


      <li class="sidebar-header">
        Request Pages
      </li>

      <li class="sidebar-item">
        <a class="sidebar-link" href="request.php">
          <i class="align-middle" data-feather="plus-square"></i><span class="align-middle">Request</span>
        </a>
      </li>

      <li class="sidebar-item">
        <a class="sidebar-link" href="status.php">
          <i class="align-middle" data-feather="check-square"></i><span class="align-middle">Request Status</span>
        </a>
      </li>

      <li class="sidebar-item">
        <a class="sidebar-link" href="request_type.php" >
          <i class="align-middle" data-feather="message-square"></i><span class="align-middle">Request Type</span>
        </a>
      </li>

      <li class="sidebar-header">
        Items
      </li>

      <li class="sidebar-item">
        <a class="sidebar-link" href="client_items.php" >
          <i class="align-middle" data-feather="package"></i><span class="align-middle">Your Items</span>
        </a>
      </li>
    </ul>

</nav>

<script src="js/app.js"></script>
