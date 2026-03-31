<?php
/**
 * Church CMS - Visual Installation Wizard
 *
 * A standalone PHP installer that works without Laravel framework
 * Handles: Requirements check, Database setup, Church & Admin configuration
 *
 * @version 1.0
 */

session_start();

// Define base paths
define('BASE_PATH', dirname(dirname(__DIR__)));
define('PUBLIC_PATH', dirname(__DIR__));
define('INSTALLER_PATH', __DIR__);
define('STORAGE_PATH', BASE_PATH . '/storage');

// Check if already installed
if (file_exists(STORAGE_PATH . '/installed')) {
    header('Location: ../');
    exit;
}

// Get current step
$step = isset($_GET['step']) ? (int)$_GET['step'] : 1;
$error = '';
$success = '';

// Handle finalize installation request (write marker file)
if (isset($_GET['action']) && $_GET['action'] === 'finalize_installation' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    // Verify installation is actually complete
    if ($step == 6 && ($_SESSION['installation_complete'] ?? false)) {
        try {
            @mkdir(STORAGE_PATH, 0755, true);
            $markerFile = STORAGE_PATH . '/installed';
            file_put_contents($markerFile, date('Y-m-d H:i:s'));
            @chmod($markerFile, 0644);

            echo json_encode(['success' => true, 'message' => 'Installation finalized']);
            exit;
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Installation not ready']);
        exit;
    }
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once INSTALLER_PATH . '/includes/functions.php';

    switch ($step) {
        case 2:
            // Requirements check - just proceed
            header('Location: ?step=3');
            exit;
            break;

        case 3:
            // Database configuration
            $result = saveDatabaseConfig($_POST);
            if ($result['success']) {
                $_SESSION['db_configured'] = true;
                $_SESSION['db_config'] = $result['config'];
                header('Location: ?step=4');
                exit;
            } else {
                $error = $result['message'];
            }
            break;

        case 4:
            // Church setup
            $result = saveChurchConfig($_POST);
            if ($result['success']) {
                $_SESSION['church_configured'] = true;
                $_SESSION['church_config'] = $result['config'];
                header('Location: ?step=5');
                exit;
            } else {
                $error = $result['message'];
            }
            break;

        case 5:
            // Admin setup
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                require_once INSTALLER_PATH . '/includes/functions.php';
                $result = saveAdminConfig($_POST);
                if ($result['success']) {
                    $_SESSION['admin_configured'] = true;
                    $_SESSION['admin_config'] = $result['config'];

                    // Show installing page and run installation
                    require_once INSTALLER_PATH . '/includes/header.php';
                    require_once INSTALLER_PATH . '/views/installing.php';
                    ob_end_flush();
                    ob_start();

                    // Run installation handler with output buffering
                    $installResult = runInstallation($_SESSION);

                    if (!$installResult['success']) {
                        $error = $installResult['message'];
                        $step = 5; // Stay on step 5 if error
                        ob_end_clean();
                    } else {
                        // Mark session as ready for step 6 (don't write marker yet - wait for button click)
                        $_SESSION['installation_complete'] = true;

                        // Small delay to ensure everything is ready
                        sleep(1);

                        // Output final redirect to completion page
                        echo "<script>
                            window.updateStatus('church', 'done', 'Installation completed!');
                            setTimeout(function() {
                                window.location.href = '?step=6';
                            }, 2000);
                        </script>";
                        ob_end_flush();
                        require_once INSTALLER_PATH . '/includes/footer.php';
                        exit;
                    }
                } else {
                    $error = $result['message'];
                }
                break;
            }
            // Fall through to normal rendering if GET request
            break;
    }
}

// Restore session data based on step
$db_config = $_SESSION['db_config'] ?? [];
$church_config = $_SESSION['church_config'] ?? [];
$admin_config = $_SESSION['admin_config'] ?? [];

// Include the view
require_once INSTALLER_PATH . '/includes/header.php';

switch ($step) {
    case 1:
        require_once INSTALLER_PATH . '/views/welcome.php';
        break;
    case 2:
        require_once INSTALLER_PATH . '/includes/functions.php';
        require_once INSTALLER_PATH . '/views/requirements.php';
        break;
    case 3:
        require_once INSTALLER_PATH . '/views/database.php';
        break;
    case 4:
        require_once INSTALLER_PATH . '/includes/functions.php';
        require_once INSTALLER_PATH . '/views/church.php';
        break;
    case 5:
        require_once INSTALLER_PATH . '/views/admin.php';
        break;
    case 6:
        require_once INSTALLER_PATH . '/views/complete.php';
        break;
    default:
        echo "Invalid step";
        break;
}

require_once INSTALLER_PATH . '/includes/footer.php';

// Clean up session after installation is complete
if ($_SESSION['installation_complete'] ?? false) {
    session_destroy();
}
