<div class="sidebar close">
    <div class="logo-details">
        <i class='bx bx-bowl-rice'></i>
        <span class="logo_name">DishCraft</span>
    </div>
    <ul class="nav-links">
        <li>
            <a href="dashboard.php">
                <i class='bx bx-grid-alt'></i>
                <span class="link_name">Dashboard</span>
            </a>
            <ul class="sub-menu blank">
                <li><a class="link_name" href="#">Dashboard</a></li>
            </ul>
        </li>
        <li>
            <a href="#">
                <i class='bx bx-bell'></i>
                <span class="link_name">Notification</span>
            </a>
        </li>
        <li>
            <div class="iocn-link">
                <a href="community.php">
                    <i class='bx bx-collection'></i>
                    <span class="link_name">My Feed</span>
                </a>
                <i class='bx bxs-chevron-down arrow'></i>
            </div>
            <ul class="sub-menu">
                <li><a class="link_name" href="community.php">MyFeed</a></li>
                <li><a href="my_posts.php">My Posts</a></li>
                <li><a href="my_comments.php">My Comments</a></li>
                <li><a href="my_likes.php">My Likes</a></li>
            </ul>
        </li>
        <li>
            <div class="iocn-link">
                <a href="#">
                    <i class='bx bx-book-content'></i>
                    <span class="link_name">Recipe</span>
                </a>
                <i class='bx bxs-chevron-down arrow'></i>
            </div>
            <ul class="sub-menu">
                <li><a class="link_name" href="#">Recipe</a></li>
                <li><a href="#">New Recipe</a></li>
                <li><a href="#">Favourite Recipe</a></li>
            </ul>
        </li>
        <?php if (isset($_SESSION['user']) && $_SESSION['user']['RoleID'] == 1): ?>
            <li>
                <a href="#">
                    <i class='bx bx-pie-chart-alt-2'></i>
                    <span class="link_name">Analytics</span>
                </a>
                <ul class="sub-menu blank">
                    <li><a class="link_name" href="#">Analytics</a></li>
                </ul>
            </li>
        <?php endif; ?>

        <?php if (isset($_SESSION['user']) && $_SESSION['user']['RoleID'] == 1): ?>
            <li>
                <a href="#">
                    <i class='bx bx-flag'></i>
                    <span class="link_name">Reports</span>
                </a>
                <ul class="sub-menu">
                    <li><a class="link_name" href="#">Post Reports</a></li>
                    <li><a class="link_name" href="#">Comment Reports</a></li>
                </ul>
            </li>
        <?php endif; ?>

        <li>
            <div class="iocn-link">
                <a href="#">
                    <i class='bx bx-food-menu'></i>
                    <span class="link_name">Meal Plans</span>
                </a>
                <i class='bx bxs-chevron-down arrow'></i>
            </div>
            <ul class="sub-menu">
                <li><a class="link_name" href="#">Meal Plans</a></li>
                <li><a href="#">My Meal Plans</a></li>
            </ul>
        </li>
        <li>
            <div class="iocn-link">
                <a href="#">
                    <i class='bx bxs-party'></i>
                    <span class="link_name">Event</span>
                </a>
                <i class='bx bxs-chevron-down arrow'></i>
            </div>
            <ul class="sub-menu">
                <li><a class="link_name" href="#">Events</a></li>
                <li><a href="#">Joined Events</a></li>
            </ul>
        </li>
        <li>
            <a href="#">
                <i class='bx bx-cog'></i>
                <span class="link_name">Setting</span>
            </a>
            <ul class="sub-menu blank">
                <li><a class="link_name" href="#">Setting</a></li>
            </ul>
        </li>
        <li>
            <div class="profile-details">
                <div class="profile-content">
                    <img src="image/profile.jpg" alt="profileImg">
                </div>
                <div class="name-job">
                    <div class="profile_name">Prem Shahi</div>
                    <div class="job">Web Desginer</div>
                </div>
                <a href="logout.php">
                <i class='bx bx-log-out'></i>
                </a>
            </div>
        </li>
    </ul>
</div>