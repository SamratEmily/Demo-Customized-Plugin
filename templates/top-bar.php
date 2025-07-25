<div class="booxos-dashboard-topbar">
	<div class="dokan-dash-sidebar-logo">
		<?php
        $logo_url = plugin_dir_url( __FILE__ ) . '../assets/images/default-logo.png';
        echo '<img src="' . esc_url( $logo_url ) . '" alt="Logo" style="max-height:35px;width:auto;">';
		?>
	</div>
	<div class="booxos-topbar-left">
        <button class="sidebar-toggle" id="sidebar-toggle-btn" type="button">
            <svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-align-left" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="21" y1="6" x2="3" y2="6"></line>
                <line x1="15" y1="12" x2="3" y2="12"></line>
                <line x1="10" y1="18" x2="3" y2="18"></line>
            </svg>
        </button>
    </div>
	<div class="booxos-topbar-right">
		<a href="<?php echo esc_url( dokan_get_store_url( dokan_get_current_user_id() ) ); ?>" target="_blank" class="booxos-round-btn booxos-outline-btn header-visit-store-btn">
			<i class="fi fi-rr-arrow-up-right-from-square"></i>
			<?php echo esc_html__( 'Visit Store', 'dokan' ); ?>
		</a>
		<div class="user-dropdown">
			<ul>
				<li>
					<a href="#" id="user-top-dropdown-toggle">
                        <?php echo "Sunshine"; ?>
					</a>
					<ul id="user-top-dropdown-menu" style="display:none; position:absolute; left:0; top:calc(100% + 6px); min-width:150px; z-index:1000;">
						<li>
							<a href="<?php echo esc_url( dokan_get_navigation_url( 'edit-account' ) ); ?>">
								<i class="fi fi-rr-member-list"></i>
								<?php echo esc_html__( 'Profile', 'dokan' ); ?>
							</a>
						</li>
						<li>
							<a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>">
								<i class="fi fi-rr-exit"></i>
								<?php echo esc_html__( 'Sign Out', 'dokan' ); ?>
							</a>
						</li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</div>
<script>
(function() {
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('sidebar-toggle-btn').addEventListener('click', function() {
            document.body.classList.toggle('sidebar-collapsed');
        });

        var toggleBtn = document.getElementById('sidebar-toggle-btn');
        var sidebar = document.querySelector('.dokan-dash-sidebar');
        if (toggleBtn && sidebar) {
            toggleBtn.addEventListener('click', function() {
                sidebar.classList.toggle('sidebar-collapsed');
                sidebar.classList.toggle('sidebar-expanded');
            });
        }

        // User dropdown logic
        var userToggle = document.getElementById('user-top-dropdown-toggle');
        var userMenu = document.getElementById('user-top-dropdown-menu');
        if (userToggle && userMenu) {
            userToggle.addEventListener('click', function(e) {
                e.preventDefault();
                userMenu.classList.toggle('active');
                if (userMenu.classList.contains('active')) {
                    userMenu.style.display = 'block';
                } else {
                    userMenu.style.display = 'none';
                }
            });
            // Hide menu when clicking outside
            document.addEventListener('click', function(e) {
                if (!userMenu.contains(e.target) && !userToggle.contains(e.target)) {
                    userMenu.classList.remove('active');
                    userMenu.style.display = 'none';
                }
            });
        }
    });
})();
</script>