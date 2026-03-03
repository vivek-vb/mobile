<?php
$pageTitle = 'My Profile';
require_once 'includes/config.php';
include 'includes/header.php';

$user_id = $_SESSION['user_id'];
$update_success = false;

// 1. Handle Update Logic
if (isset($_POST['save_profile'])) {
    $fname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    mysqli_query($conn, "UPDATE `user_info` SET firstname='$fname', lastname='$lname', email='$email' WHERE id='$user_id'");
    $update_success = true;
}

// 2. Fetch User Data
$user_query = mysqli_query($conn, "SELECT * FROM `user_info` WHERE id = '$user_id'");
$user = mysqli_fetch_assoc($user_query);

$initial = substr($user['firstname'] ?? 'U', 0, 1);
?>

<style>
    .profile-container { max-width: 500px; margin: 60px auto; padding: 0 20px; flex: 1; }
    
    .profile-card {
        background: #fff;
        border-radius: 30px;
        padding: 50px 40px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.05);
        text-align: center;
        border: 1px solid #f0f0f0;
    }

    .avatar {
        width: 100px; height: 100px;
        background: linear-gradient(135deg, #0071e3, #a855f7);
        color: #fff; font-size: 2.8rem; font-weight: 800;
        display: flex; align-items: center; justify-content: center;
        border-radius: 50%; margin: 0 auto 30px;
        text-transform: uppercase;
        box-shadow: 0 10px 20px rgba(0, 113, 227, 0.2);
    }

    .status-msg { background: #e6f4ea; color: #1e8e3e; padding: 12px; border-radius: 12px; margin-bottom: 20px; font-weight: 600; font-size: 0.9rem; }

    /* Info Display */
    .info-display { margin-bottom: 35px; }
    .info-display h2 { font-size: 2rem; font-weight: 800; color: #1d1d1f; margin-bottom: 10px; }
    .email-box { color: #86868b; font-size: 1.1rem; }

    /* Form Visibility */
    .edit-form { text-align: left; display: none; }
    .input-box { margin-bottom: 15px; }
    .input-box label { display: block; font-size: 0.85rem; color: #86868b; margin-bottom: 5px; font-weight: 600; }
    .input-box input { width: 100%; padding: 14px; border-radius: 12px; border: 1px solid #d2d2d7; font-size: 1rem; }

    .btn-row { display: flex; flex-direction: column; gap: 12px; margin-top: 20px; }
    .btn { padding: 14px 28px; border-radius: 14px; text-decoration: none; font-weight: 700; cursor: pointer; border: none; font-size: 1rem; transition: 0.3s; }
    .btn-primary { background: #0071e3; color: #fff; }
    .btn-light { background: #f5f5f7; color: #1d1d1f; }
    .btn-danger { color: #ff3b30; font-size: 0.9rem; margin-top: 20px; display: inline-block; text-decoration: none; font-weight: 600; }

    .is-editing #profile-view { display: none; }
    .is-editing #profile-edit-form { display: block; }
</style>

<div class="profile-container" id="profile-parent">
    <div class="profile-card">
        <div class="avatar"><?php echo $initial; ?></div>

        <?php if($update_success) echo '<div class="status-msg">Profile updated successfully!</div>'; ?>

        <div id="profile-view">
            <div class="info-display">
                <h2><?php echo htmlspecialchars($user['firstname'].' '.$user['lastname']); ?></h2>
                <p class="email-box"><?php echo htmlspecialchars($user['email']); ?></p>
            </div>
            
            <div class="btn-row">
                <button onclick="toggleEdit()" class="btn btn-primary">Edit Account</button>
                <a href="index.php?logout=<?php echo $user_id; ?>" onclick="return confirm('Log Out?');" class="btn-danger">Sign Out</a>
            </div>
        </div>

        <form id="profile-edit-form" class="edit-form" method="POST">
            <h3 style="margin-bottom: 20px; text-align: center; font-weight: 800;">Edit Details</h3>
            <div class="input-box">
                <label>First Name</label>
                <input type="text" name="firstname" value="<?php echo htmlspecialchars($user['firstname']); ?>" required>
            </div>
            <div class="input-box">
                <label>Last Name</label>
                <input type="text" name="lastname" value="<?php echo htmlspecialchars($user['lastname']); ?>" required>
            </div>
            <div class="input-box">
                <label>Email Address</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>

            <div class="btn-row">
                <button type="submit" name="save_profile" class="btn btn-primary">Save Changes</button>
                <button type="button" onclick="toggleEdit()" class="btn btn-light">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleEdit() {
        document.getElementById('profile-parent').classList.toggle('is-editing');
    }
</script>

<?php include 'includes/footer.php'; ?>