<header class="navbar">
    <div class="nav-container">

        <!-- Logo -->
        <div class="nav-logo">
            
        </div>

        <!-- Right Menu -->
        <nav class="nav-menu">

            <!-- Guest -->
            <?php if (!isset($_SESSION['user'])): ?>
                <a href="?url=user/login">Login</a>
                <a href="?url=user/register" class="btn-primary">Register</a>
               

            <!-- Logged in -->
            <?php else: ?>
                <?php if (isset($_SESSION['user']) && in_array($_SESSION['user']['role'], ['USER', 'VENDOR'])): ?>
    <a href="?url=tool/create">Create Tool</a>
    <a href="?url=user/ViewForAllUser">Home</a>
    
<?php endif; ?>


                <!-- Admin Menus -->
                <?php if ($_SESSION['user']['role'] === 'ADMIN'): ?>
                    <a href="?url=user/ViewForAllUser">Home</a>
                    <a href="?url=admin/users">All Users</a>
                    <a href="?url=admin/vendors">All Vendors</a>
                    <a href="?url=admin/manageAdmins">Manage Admin</a>
                    <a href="?url=category/index">Manage Categories</a>
                <?php endif; ?>

                <!-- User/Vendor/Admin Dropdown -->
                <div class="dropdown">
                    <button class="dropdown-btn">
                        <?= htmlspecialchars($_SESSION['user']['name']) ?> ⌄
                    </button>

                    <div class="dropdown-menu">
                        <a href="?url=user/profile">My Profile</a>
                        <a href="?url=user/updateProfile">Update Profile</a>
                        <a href="?url=user/changePassword">Change Password</a>
                        <a href="?url=user/deactivateAccount" class="danger">
                            Deactivate Account
                        </a>
                        <hr>
                        <a href="?url=user/logout">Logout</a>
                    </div>
                </div>

            <?php endif; ?>

        </nav>
    </div>
</header>
