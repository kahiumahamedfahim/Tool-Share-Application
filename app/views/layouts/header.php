<header class="navbar">
    <div class="nav-container">

        <!-- Logo -->
        <div class="nav-logo">
            <a href="?url=user/ViewForAllUser" class="logo-text">
                🔧 ToolShare
                <span class="logo-sub">Tool Sharing Application</span>
            </a>
        </div>

        <!-- Navigation -->
        <nav class="nav-menu">

            <!-- Guest -->
            <?php if (!isset($_SESSION['user'])): ?>
                <a href="?url=user/login">Login</a>
                <a href="?url=user/register" class="btn-primary">Register</a>

            <!-- Logged In -->
            <?php else: ?>

                <!-- USER / VENDOR -->
                <?php if (in_array($_SESSION['user']['role'], ['USER', 'VENDOR'])): ?>
                   
                    
                    <?php if ($_SESSION['user']['role'] === 'USER'): ?>
                       
                         <a href="?url=user/ViewForAllUser">Home</a>
                    <a href="?url=rent/myRequests">My Rent Requests</a>
                    <a href="?url=rentalLog/my">My Rental Logs</a>
                     <?php endif; ?>  

                <?php endif; ?>
                <?php if ($_SESSION['user']['role'] === 'VENDOR'): ?>
                    <a href="?url=user/ViewForAllUser">Home</a>
                    <a href="?url=tool/myTools">My Tools</a>
                    <a href="?url=rent/ownerRequests">Incoming Requests</a>
                     <a href="?url=rentalLog/owner">Rental Logs</a>
                    <a href="?url=tool/create">Create Tool</a>
                                    <?php endif; ?>    
                   

                <!-- ADMIN -->
                <?php if ($_SESSION['user']['role'] === 'ADMIN'): ?>
                    <a href="?url=user/ViewForAllUser">Home</a>
                    <a href="?url=admin/users">All Users</a>
                    <a href="?url=admin/vendors">All Vendors</a>
                    <a href="?url=admin/manageAdmins">Manage Admins</a>
                    <a href="?url=rent/adminRequests">All Rent Requests</a>
                    <a href="?url=rentalLog/admin">Rental Logs</a>
                    <a href="?url=category/index">Categories</a>
                <?php endif; ?>

                <!-- Profile Dropdown -->
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
