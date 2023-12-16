<?php
if (!class_exists('WP_Plugins_List_Table')) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-plugins-list-table.php';
}

$config = crtl_get_plugin_config();
?>
<script>
    function onClickSync(e) {
        e.preventDefault();
        const url = e.currentTarget.getAttribute("href");

        if (window.confirm("Are you sure you want to sync this plugins version with the currently installed version?")) {
            window.location.href = url;
        }
    }

    function onClickUntrack(e) {
        e.preventDefault();
        const url = e.currentTarget.getAttribute("href");

        if (window.confirm("Are you sure you want to untrack this plugin?")) {
            window.location.href = url;
        }
    }
</script>
<style>
    .tab-content {
        display: none;
    }

    .tab-content:first-of-type {
        display: block;
    }

    .wp-list-table th {
        white-space: nowrap;
    }
</style>
<div class="wrap">
    <h1>Crtl Plugin Manager</h1>

    <h2 class="nav-tab-wrapper">
        <a href="#tracked" class="nav-tab nav-tab-active">Tracked Plugins</a>
        <a href="#installed" class="nav-tab">Installed Plugins</a>
    </h2>

    <div id="tracked" class="tab-content">
        <table class="wp-list-table widefat striped">
            <thead>
            <tr>
                <th>Plugin Name</th>
                <th>Description</th>
                <th>Required Version</th>
                <th>Installed Version</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($config as $name => $value): ?>
                <tr>
                    <td><?php echo esc_html($value["plugin_data"]["Name"]); ?></td>
                    <td><?php echo esc_html($value["plugin_data"]["Description"]); ?></td>
                    <td><?php echo esc_html($value["required_version"]); ?></td>
                    <td><?php echo esc_html($value["version"]); ?></td>
                    <td>
                        <?php $nameEncoded = rawurlencode($name); ?>
                        <a href="<?php echo esc_url(admin_url('plugins.php?action=deactivate&plugin=' . $nameEncoded)); ?>">Deactivate</a> |
                        <a href="<?php echo esc_url(admin_url('plugins.php?action=activate&plugin=' . $nameEncoded)); ?>">Activate</a> |
                        <a href="<?php echo esc_url(admin_url('admin.php?page=crtl-plugin-manager&action=sync&plugin=' . $nameEncoded)); ?>" onclick="onClickSync(this)">Sync</a> |
                        <a href="<?php echo esc_url(admin_url('admin.php?page=crtl-plugin-manager&action=untrack&plugin=' . $nameEncoded)); ?>" onclick="onClickUntrack(this)">Untrack</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div id="installed" class="tab-content">
        <table class="wp-list-table widefat striped">
            <thead>
            <tr>
                <th>Plugin Name</th>
                <th>Description</th>
                <th>Version</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php
            // Get the list of active plugins
            $active_plugins = get_option('active_plugins');

            foreach ($active_plugins as $plugin) {
                $plugin_data = get_plugin_data(WP_PLUGIN_DIR . '/' . $plugin);
                ?>
                <tr>
                    <td><?php echo esc_html($plugin_data['Name']); ?></td>
                    <td><?php echo esc_html($plugin_data['Description']); ?></td>
                    <td><?php echo esc_html($plugin_data['Version']); ?></td>
                    <td>

                    </td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    // JavaScript to handle tab switching
    const tabs = document.querySelectorAll('.nav-tab');
    const tabContents = document.querySelectorAll('.tab-content');

    tabs.forEach(function (tab, index) {
        tab.addEventListener('click', function (event) {
            event.preventDefault();

            // Remove active class from all tabs and tab contents
            tabs.forEach(function (t) {
                t.classList.remove('nav-tab-active');
            });
            tabContents.forEach(function (content) {
                content.style.display = 'none';
            });

            // Add active class to the clicked tab and display corresponding content
            tab.classList.add('nav-tab-active');

            console.log("show", tabContents[index]);
            tabContents[index].style.display = 'block';
        });
    });


</script>
