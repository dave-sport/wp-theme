<?php
/*
Template Name: Register
*/

get_header();

/* =========================
   PASSWORD PROTECTION
========================= */
if ( post_password_required() ) {
get_header();
    echo get_the_password_form();
    get_footer();
    return;
}

/* =========================
   FORM PROCESSING
========================= */
$msg = '';

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['author_register_nonce']) &&
    wp_verify_nonce($_POST['author_register_nonce'], 'author_register')
) {

    // 🔒 RATE LIMITING
    $ip = $_SERVER['REMOTE_ADDR'];
    $attempts = get_transient('reg_attempts_' . md5($ip));

    if ($attempts && $attempts >= 3) {
        $msg = '<div class="alert alert-danger">Too many registration attempts. Please try again after 1 hour.</div>';
    } else {

        $username   = sanitize_user($_POST['username']);
        $email      = sanitize_email($_POST['email']);
        $password   = $_POST['password'];
        $repassword = $_POST['repassword'];
        $role       = in_array($_POST['role'], ['author','editor']) ? $_POST['role'] : 'author';
        $first      = sanitize_text_field($_POST['first_name']);
        $last       = sanitize_text_field($_POST['last_name']);

        // 🔒 SERVER-SIDE PASSWORD VALIDATION
        if (strlen($password) < 8) {
            $msg = '<div class="alert alert-danger">Password must be at least 8 characters long.</div>';

        } elseif ($password !== $repassword) {
            $msg = '<div class="alert alert-danger">Passwords do not match.</div>';

        } elseif (username_exists($username) || email_exists($email)) {
            $msg = '<div class="alert alert-warning">Username or Email already exists.</div>';

        } else {

            $user_id = wp_create_user($username, $password, $email);

            if (!is_wp_error($user_id)) {

                wp_update_user([
                    'ID' => $user_id,
                    'first_name' => $first,
                    'last_name'  => $last,
                    'display_name' => trim("$first $last")
                ]);

                // 🔴 SET PENDING STATUS - NO ROLE ASSIGNED
                $user = new WP_User($user_id);
                $user->set_role(''); // NO ROLE → NOT APPROVED

                update_user_meta($user_id, 'account_status', 'pending');
                update_user_meta($user_id, 'requested_role', $role);

                // 🔒 LOG REGISTRATION DATE
                update_user_meta($user_id, 'registration_date', current_time('mysql'));

                $msg = '<div class="alert alert-success">
                            Registration successful!<br>
                            Your account is pending admin approval.<br>
                            You will be notified via email once approved.
                        </div>';

                // 🔒 RESET RATE LIMIT ON SUCCESSFUL REGISTRATION
                delete_transient('reg_attempts_' . md5($ip));

            } else {
                $msg = '<div class="alert alert-danger">' . esc_html($user_id->get_error_message()) . '</div>';
            }
        }

        // 🔒 INCREMENT RATE LIMIT COUNTER
        if (!isset($msg) || strpos($msg, 'success') === false) {
            set_transient('reg_attempts_' . md5($ip), ($attempts ? $attempts + 1 : 1), 3600);
        }
    }
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
body { background:#f1f1f1; font-family:"Open Sans",sans-serif; }
.reg-bx { min-height:100vh; display:flex; align-items:center; justify-content:center; padding:20px; }
.reg-card {
    max-width:600px;
    width:100%;
    background:#fff;
    border-radius:8px;
    box-shadow:0 4px 25px rgba(0,0,0,0.1);
    padding:30px;
    border:1px solid #ccd0d4;
}
.form-control { height:45px; }
.toggle-pass { border:1px solid #ccd0d4; }
#strengthText { font-size:0.85rem; margin-top:5px; display:block; }
.strength-weak { color:#dc3545; }
.strength-fair { color:#ffc107; }
.strength-good { color:#0dcaf0; }
.strength-strong { color:#198754; }
</style>

<div class="reg-bx">
<div class="reg-card">

<h3 class="text-center mb-3">Register as Author / Editor</h3>

<?php if ($msg) echo $msg; ?>

<form method="post" id="registerForm">
<?php wp_nonce_field('author_register','author_register_nonce'); ?>

<div class="row">

<div class="col-md-6 mb-3">
<input class="form-control" name="first_name" placeholder="First Name" required 
       pattern="[A-Za-z\s]+" title="Only letters allowed">
</div>

<div class="col-md-6 mb-3">
<input class="form-control" name="last_name" placeholder="Last Name" required
       pattern="[A-Za-z\s]+" title="Only letters allowed">
</div>

<div class="col-md-6 mb-3">
<input class="form-control" name="username" placeholder="Username" required
       pattern="[a-zA-Z0-9_-]+" minlength="3" title="Username must be 3+ characters, only letters, numbers, - and _">
</div>

<div class="col-md-6 mb-3">
<input class="form-control" type="email" name="email" placeholder="Email" required>
</div>

<div class="col-md-6 mb-3">
<div class="input-group">
<input class="form-control" id="password" name="password" type="password" 
       placeholder="Password (min 8 chars)" required minlength="8">
<button class="btn toggle-pass" type="button" data-target="password">
<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
</button>
</div>
<small id="strengthText"></small>
</div>

<div class="col-md-6 mb-3">
<div class="input-group">
<input class="form-control" id="repassword" name="repassword" type="password" 
       placeholder="Retype Password" required minlength="8">
<button class="btn toggle-pass" type="button" data-target="repassword">
<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
</button>
</div>
</div>

</div>

<select name="role" class="form-select mb-3" required>
<option value="">Select Role</option>
<option value="author">Author</option>
<option value="editor">Editor</option>
</select>

<button class="btn btn-primary w-100" type="submit">Register</button>

<p class="login-text" style="margin-top:10px;">
            Already have an account?
            <a href="<?php echo home_url(); ?>/daveadminlogin/" style="color:#000;">Click here to login</a>
        </p>
</form>

</div>
</div>

<script>
const pass = document.getElementById("password");
const repass = document.getElementById("repassword");
const strengthText = document.getElementById("strengthText");
const form = document.getElementById("registerForm");

// 🔒 TOGGLE PASSWORD VISIBILITY
document.querySelectorAll(".toggle-pass").forEach(btn => {
    btn.onclick = () => {
        const input = document.getElementById(btn.dataset.target);
        const icon = btn.querySelector("i");
        input.type = input.type === "password" ? "text" : "password";
        icon.innerHTML = input.type === "password" ? '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>' : '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>';
    };
});

// 🔒 PASSWORD STRENGTH METER
pass.oninput = () => {
    let s = 0;
    if (pass.value.length >= 8) s++;
    if (/[A-Z]/.test(pass.value)) s++;
    if (/[0-9]/.test(pass.value)) s++;
    if (/[^A-Za-z0-9]/.test(pass.value)) s++;

    const levels = [
        { text: "Weak", class: "strength-weak" },
        { text: "Fair", class: "strength-fair" },
        { text: "Good", class: "strength-good" },
        { text: "Strong", class: "strength-strong" }
    ];
    
    if (s > 0) {
        const level = levels[s - 1];
        strengthText.innerHTML = `Strength: <b class="${level.class}">${level.text}</b>`;
    } else {
        strengthText.innerHTML = "";
    }
};

// 🔒 CLIENT-SIDE VALIDATION
form.onsubmit = (e) => {
    if (pass.value !== repass.value) {
        e.preventDefault();
        alert("Passwords do not match!");
        return false;
    }
    if (pass.value.length < 8) {
        e.preventDefault();
        alert("Password must be at least 8 characters long!");
        return false;
    }
};
</script>

<?php get_footer(); ?>